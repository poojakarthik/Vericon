#!/usr/bin/perl

use WWW::Mechanize;
use HTML::TableExtract;

require HTTP::Cookies;

unless (@ARGV == 1 || $ENV{'QUERY_STRING'}){
    die "Usage: $0 <Service Number>\n";
}

if($ENV{'QUERY_STRING'}){
   print "Content-type: application/json\r\n\r\n";
}

my $ph = $ARGV[0]?$ARGV[0]:$ENV{'QUERY_STRING'};

if($ph !~ /[0-9]{8,10}/){
    print qq/{\n\t"error": "Bad Input"\n\t"msg": "The number entered is invalid"\n}\n/;
    exit;
}

my $mech = WWW::Mechanize->new( cookie_jar => HTTP::Cookies->new(file  => "/tmp/NZ_SCR.txt", autosave=>1, ignore_discard=>1 )  );

$mech->get('https://www.wireline.co.nz/ServiceProvider/Dashboard/WirelineOrders/IntactLineCheck.aspx');

if($mech->uri()->as_string =~ /Login/){ 
    $mech->submit_form(
	form_name => 'Login',
	fields    => { LoginNameTextBox => 'finbar@smartbusinesstelecom.com.au', LoginPasswordTextBox => 'somoxge18450be' },
	button    => 'LoginButton');

    $mech->get('https://www.wireline.co.nz/ServiceProvider/Dashboard/WirelineOrders/IntactLineCheck.aspx');
}

$mech->submit_form(
    form_name => 'aspnetForm',
    fields    => { 'ctl00$MainPlaceHolder$SelectionList' => 'LineNumber' },
    button    => '');

my $r = $mech->submit_form(
    form_name => 'aspnetForm',
    fields    => { 
	'ctl00$MainPlaceHolder$SelectionList' => 'LineNumber',
	'ctl00$MainPlaceHolder$LineNumberQuery$txtLineNumber' => $ph,
	'ctl00$MainPlaceHolder$LineNumberQuery$drpServiceProviders' => '54540385',
    },   button => 'ctl00$MainPlaceHolder$LineNumberQuery$QuerySiteAndServiceButton');

my $data =  $r->decoded_content;

if($data =~ /Invalid Line/){
    print qq/{\n\t"error": "Invalid Line",\n\t"msg": "The line is not currently connected or is still pending connection"\n}\n/;
    exit;
}

if($data =~ /quota limit for the day has been reached/){
    print qq/{\n\t"error": "Quota Limit Reached",\n\t"msg": "The quota limit has been reached for the day"\n}\n/;
    exit;
}

if($data !~ /Version 6.4.11/){
    print qq/{\n\t"error": "Version Mismatch",\n\t"msg": "Wireline version has changed, Verify HTML validity"\n}\n/;
    exit;
}

$data =~ s/\t//g;
$data =~ s/ +/ /g;

$te = HTML::TableExtract->new( );
$te->parse($data);

$out = qq/{\n\t"search": "$ph",\n/;

@t = $te->table(2, 0)->rows();
$out .= qq/\t"sam": "$t[1][0]",\n/;
$out .= qq/\t"adr": "$t[1][1]",\n/;

@t = $te->table(2, 1)->rows();
$out .= qq/\t"zone": "$t[0][1]",\n/;
$out .= qq/\t"density": "$t[1][1]",\n/;
$out .= qq/\t"urbanisation": "$t[2][1]",\n/;

$out .= qq/\t"service": [\n/;
foreach $r ($te->table(2, 2)->rows()){
    next if @$r[0] eq 'Line Type';
    $out .= qq/\t\t{\n\t\t\t"type": "@$r[0]",\n/; 
    $out .= qq/\t\t\t"serviceID": "@$r[1]",\n/; 
    $out .= qq/\t\t\t"openSO": "@$r[2]",\n/; 
    $out .= qq/\t\t\t"switch": "@$r[3]",\n/; 
    $out .= qq/\t\t\t"DirectoryListing": "@$r[4]"\n\t\t},/; 
}
chop $out;
$out .= qq/\n\t],\n/;

$out .= qq/\t"orders": [\n/;
foreach $r ($te->table(2, 3)->rows()){
    next if @$r[0] eq 'Line Number';
    $out .= qq/\t\t{\n\t\t\t"lineNumber": "@$r[0]",\n/; 
    $out .= qq/\t\t\t"dueDate": "@$r[1]",\n/; 
    $out .= qq/\t\t\t"serviceOrderClass": "@$r[2]"\n\t\t},/; 
}
chop $out;
$out .= qq/\n\t]\n}\n/;

print $out;