#include "mainwindow.h"
#include "ui_mainwindow.h"

QString getMacAddress()
{
    foreach(QNetworkInterface interface, QNetworkInterface::allInterfaces())
    {
        if (!(interface.flags() & QNetworkInterface::IsLoopBack))
            return interface.hardwareAddress();
    }
    return QString();
}

MainWindow::MainWindow(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::MainWindow)
{
    ui->setupUi(this);
    ui->tabWidget->removeTab(1);
    ui->centralWidget->setLayout(ui->verticalLayout);
    ui->tab->setLayout(ui->verticalLayout_2);
    ui->verticalLayout_2->addWidget(ui->webView);
    ui->webView->setFocus();

    // Main Page
    QNetworkRequest request;
    request.setRawHeader(
                QString("MAC").toAscii(),
                QString(getMacAddress()).toAscii()
                );
    request.setUrl(QUrl("http://mail.vericon.com.au/"));
    ui->webView->settings()->setAttribute(QWebSettings::PluginsEnabled, true);
    ui->webView->settings()->setAttribute(QWebSettings::JavascriptEnabled, true);
    ui->webView->settings()->setAttribute(QWebSettings::JavascriptCanCloseWindows, true);
    ui->webView->settings()->setAttribute(QWebSettings::JavascriptCanOpenWindows, true);
    ui->webView->settings()->setAttribute(QWebSettings::LocalContentCanAccessRemoteUrls, true);
    ui->webView->settings()->setAttribute(QWebSettings::AutoLoadImages, true);
    ui->webView->setContextMenuPolicy(Qt::NoContextMenu);
    connect(ui->webView, SIGNAL(titleChanged(QString)), SLOT(adjustTitle()));
    connect(ui->webView, SIGNAL(loadFinished(bool)), SLOT(checkPage()));
    ui->webView->load(request);

    // Knowledge Base
    ui->webView_2->settings()->setAttribute(QWebSettings::PluginsEnabled, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::JavascriptEnabled, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::JavascriptCanCloseWindows, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::JavascriptCanOpenWindows, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::LocalContentCanAccessRemoteUrls, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::AutoLoadImages, true);
    ui->webView_2->setContextMenuPolicy(Qt::NoContextMenu);
    connect(ui->webView_2, SIGNAL(loadFinished(bool)), SLOT(checkPage_2()));

    // Email
    ui->webView_3->settings()->setAttribute(QWebSettings::PluginsEnabled, true);
    ui->webView_3->settings()->setAttribute(QWebSettings::JavascriptEnabled, true);
    ui->webView_3->settings()->setAttribute(QWebSettings::JavascriptCanCloseWindows, true);
    ui->webView_3->settings()->setAttribute(QWebSettings::JavascriptCanOpenWindows, true);
    ui->webView_3->settings()->setAttribute(QWebSettings::LocalContentCanAccessRemoteUrls, true);
    ui->webView_3->settings()->setAttribute(QWebSettings::AutoLoadImages, true);
    ui->webView_3->setContextMenuPolicy(Qt::NoContextMenu);
    connect(ui->webView_3, SIGNAL(loadFinished(bool)), SLOT(checkPage_3()));
}

void MainWindow::adjustTitle()
{
    QString title = "VeriCon :: ";
    title.append(ui->webView->title());
    ui->tabWidget->setTabText(0, title);
}

void MainWindow::checkPage()
{
    QUrl url = ui->webView->url();
    if (url.toString().endsWith("/main/"))
    {
        ui->tabWidget->addTab(new QWidget(), QIcon(":/new/images/kb.png"), "VeriCon :: Knowledge Base");
        ui->tabWidget->widget(1)->setLayout(ui->verticalLayout_3);
        ui->verticalLayout_3->addWidget(ui->webView_2);
        ui->webView_2->page()->networkAccessManager()->setCookieJar(ui->webView->page()->networkAccessManager()->cookieJar());
        QNetworkRequest request_2;
        request_2.setUrl(QUrl("https://mail.vericon.com.au/kb/"));
        ui->webView_2->load(request_2);

        ui->tabWidget->addTab(new QWidget(), QIcon(":/new/images/email.png"), "VeriCon :: Mail");
        ui->tabWidget->widget(2)->setLayout(ui->verticalLayout_4);
        ui->verticalLayout_4->addWidget(ui->webView_3);
        ui->webView_3->page()->networkAccessManager()->setCookieJar(ui->webView->page()->networkAccessManager()->cookieJar());
        QNetworkRequest request_3;
        request_3.setUrl(QUrl("https://mail.vericon.com.au/auth/mail_login.php"));
        ui->webView_3->load(request_3);
    }
    else if (url.toString().endsWith("/logout/"))
    {
        ui->tabWidget->removeTab(2);
        ui->tabWidget->removeTab(1);
    }
}

void MainWindow::checkPage_2()
{
    QUrl url = ui->webView_2->url();
    if (url.toString().endsWith("/logout/"))
    {
        QNetworkRequest request;
        request.setUrl(QUrl("https://mail.vericon.com.au/logout/"));
        ui->webView->load(request);
        ui->tabWidget->removeTab(2);
        ui->tabWidget->removeTab(1);
    }
}

void MainWindow::checkPage_3()
{
    QUrl url = ui->webView_3->url();
    if (url.toString().endsWith("/mail/login/"))
    {
        ui->webView_3->page()->networkAccessManager()->setCookieJar(ui->webView->page()->networkAccessManager()->cookieJar());
        QNetworkRequest request;
        request.setUrl(QUrl("https://mail.vericon.com.au/auth/mail_login.php"));
        ui->webView_3->load(request);
    }
}

MainWindow::~MainWindow()
{
    delete ui;
}
