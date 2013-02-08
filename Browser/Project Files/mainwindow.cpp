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
    ui->centralWidget->setLayout(ui->verticalLayout);
    ui->tab->setLayout(ui->verticalLayout_2);
    ui->verticalLayout_2->addWidget(ui->webView);
    ui->tab_2->setLayout(ui->verticalLayout_3);
    ui->verticalLayout_3->addWidget(ui->webView_2);
    ui->tabWidget->setTabEnabled(1, false);

    QNetworkRequest request;
    request.setRawHeader(
                QString("MAC").toAscii(),
                QString(getMacAddress()).toAscii()
                );
    request.setUrl(QUrl("https://mail.vericon.com.au/"));

    webPage * page = new webPage();
    ui->webView->setPage((QWebPage*)page);
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

    webPage * page2 = new webPage();
    ui->webView_2->setPage((QWebPage*)page2);
    ui->webView_2->settings()->setAttribute(QWebSettings::PluginsEnabled, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::JavascriptEnabled, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::JavascriptCanCloseWindows, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::JavascriptCanOpenWindows, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::LocalContentCanAccessRemoteUrls, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::AutoLoadImages, true);
    ui->webView_2->setContextMenuPolicy(Qt::NoContextMenu);
    connect(ui->webView_2, SIGNAL(titleChanged(QString)), SLOT(adjustTitle_2()));
    connect(ui->webView_2, SIGNAL(loadFinished(bool)), SLOT(checkPage_2()));
}

void MainWindow::adjustTitle()
{
    QString title = "VeriCon :: ";
    title.append(ui->webView->title());
    ui->tabWidget->setTabText(0, title);
}

void MainWindow::adjustTitle_2()
{
    ui->tabWidget->setTabText(1, ui->webView_2->title());
}

void MainWindow::checkPage()
{
    QUrl url = ui->webView->url();
    if (url.toString().endsWith("/main/"))
    {
        ui->webView_2->page()->networkAccessManager()->setCookieJar(ui->webView->page()->networkAccessManager()->cookieJar());
        QNetworkRequest request_2;
        request_2.setUrl(QUrl("https://mail.vericon.com.au/auth/mail_login.php"));
        ui->webView_2->load(request_2);
        ui->tabWidget->setTabEnabled(1, true);
    }
    else if (url.toString().endsWith("/logout/"))
    {
        ui->tabWidget->setTabText(1, "VeriCon :: Mail");
        ui->tabWidget->setTabEnabled(1, false);
    }
}

void MainWindow::checkPage_2()
{
    QUrl url = ui->webView_2->url();
    if (url.toString().endsWith("/mail/login/"))
    {
        ui->webView_2->page()->networkAccessManager()->setCookieJar(ui->webView->page()->networkAccessManager()->cookieJar());
        QNetworkRequest request_2;
        request_2.setUrl(QUrl("https://mail.vericon.com.au/auth/mail_login.php"));
        ui->webView_2->load(request_2);
    }
}

MainWindow::~MainWindow()
{
    delete ui;
}
