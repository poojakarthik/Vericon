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
    ui->verticalLayout->addWidget(ui->webView);

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
    ui->webView->load(request);
}

void MainWindow::adjustTitle()
{
    QString title = "VeriCon :: ";
    title.append(ui->webView->title());
    setWindowTitle(title);
}

MainWindow::~MainWindow()
{
    delete ui;
}
