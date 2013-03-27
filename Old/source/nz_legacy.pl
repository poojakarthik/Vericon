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
	fields    => { LoginNameTextBox => 'finbar@smartbusinesstelecom.com.au', LoginPasswordTextBox => 'somoxoge18450be0' },
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
    print "invalid";
    exit;
}

if($data =~ /Application Error/){
    print "error";
    exit;
}

if($data =~ /quota limit for the day has been reached/){
    print "error";
    exit;
}

if($data !~ /Version 11.0.0/){
    print "error";
    exit;
}

$data =~ s/\t//g;
$data =~ s/ +/ /g;

$te = HTML::TableExtract->new( );
$te->parse($data);

$out = "other";
foreach $r ($te->table(2, 2)->rows()){
    next if @$r[0] eq 'Line Type';
	if (@$r[1] == $ph)
	{
		if (@$r[0] =~ /Legacy/)
		{
			$out = "legacy";
		}
	}
}

print $out;