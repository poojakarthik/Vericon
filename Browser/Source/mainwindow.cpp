#include "mainwindow.h"
#include "ui_mainwindow.h"

QFile *file;
QNetworkReply *reply;
QDialog *downloadDialog;
QGridLayout *gridLayout;
QLabel *label;
QProgressBar *progressBar;
QString Path;

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
    ui->webView->hide();

    // Main Page
    QNetworkRequest request;
    request.setRawHeader(
                QString("MAC").toUtf8(),
                QString(getMacAddress()).toUtf8()
                );
    request.setUrl(QUrl("http://www.vericon.com.au/"));
    ui->webView->settings()->setAttribute(QWebSettings::PluginsEnabled, true);
    ui->webView->settings()->setAttribute(QWebSettings::JavascriptEnabled, true);
    ui->webView->settings()->setAttribute(QWebSettings::JavascriptCanCloseWindows, true);
    ui->webView->settings()->setAttribute(QWebSettings::JavascriptCanOpenWindows, true);
    ui->webView->settings()->setAttribute(QWebSettings::LocalContentCanAccessRemoteUrls, true);
    ui->webView->settings()->setAttribute(QWebSettings::AutoLoadImages, true);
    ui->webView->page()->setForwardUnsupportedContent(true);
    ui->webView->setContextMenuPolicy(Qt::NoContextMenu);
    connect(ui->webView, SIGNAL(titleChanged(QString)), SLOT(adjustTitle()));
    connect(ui->webView, SIGNAL(loadFinished(bool)), SLOT(checkPage()));
    connect(ui->webView->page(), SIGNAL(unsupportedContent(QNetworkReply*)), this, SLOT(download(QNetworkReply*)));
    ui->webView->load(request);

    // Knowledge Base
    ui->webView_2->settings()->setAttribute(QWebSettings::PluginsEnabled, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::JavascriptEnabled, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::JavascriptCanCloseWindows, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::JavascriptCanOpenWindows, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::LocalContentCanAccessRemoteUrls, true);
    ui->webView_2->settings()->setAttribute(QWebSettings::AutoLoadImages, true);
    ui->webView_2->page()->setForwardUnsupportedContent(true);
    ui->webView_2->setContextMenuPolicy(Qt::NoContextMenu);
    connect(ui->webView_2, SIGNAL(loadFinished(bool)), SLOT(checkPage_2()));
    connect(ui->webView_2->page(), SIGNAL(unsupportedContent(QNetworkReply*)), this, SLOT(download(QNetworkReply*)));

    // Email
    ui->webView_3->settings()->setAttribute(QWebSettings::PluginsEnabled, true);
    ui->webView_3->settings()->setAttribute(QWebSettings::JavascriptEnabled, true);
    ui->webView_3->settings()->setAttribute(QWebSettings::JavascriptCanCloseWindows, true);
    ui->webView_3->settings()->setAttribute(QWebSettings::JavascriptCanOpenWindows, true);
    ui->webView_3->settings()->setAttribute(QWebSettings::LocalContentCanAccessRemoteUrls, true);
    ui->webView_3->settings()->setAttribute(QWebSettings::AutoLoadImages, true);
    ui->webView_3->page()->setForwardUnsupportedContent(true);
    ui->webView_3->setContextMenuPolicy(Qt::NoContextMenu);
    connect(ui->webView_3, SIGNAL(loadFinished(bool)), SLOT(checkPage_3()));
    connect(ui->webView_3->page(), SIGNAL(unsupportedContent(QNetworkReply*)), this, SLOT(download(QNetworkReply*)));
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
    if (url.toString().endsWith("/login/") && ui->webView_loader->isVisible())
    {
        ui->webView->show();
        ui->webView_loader->hide();
        ui->webView->setFocus();
    }
    else if (url.toString().endsWith("/main/"))
    {
        ui->tabWidget->addTab(new QWidget(), QIcon(":/new/images/kb.png"), "VeriCon :: Knowledge Base");
        ui->tabWidget->widget(1)->setLayout(ui->verticalLayout_3);
        ui->verticalLayout_3->addWidget(ui->webView_2);
        ui->webView_2->page()->networkAccessManager()->setCookieJar(ui->webView->page()->networkAccessManager()->cookieJar());
        QNetworkRequest request_2;
        request_2.setUrl(QUrl("http://www.vericon.com.au/kb/"));
        ui->webView_2->load(request_2);

        ui->tabWidget->addTab(new QWidget(), QIcon(":/new/images/email.png"), "VeriCon :: Mail");
        ui->tabWidget->widget(2)->setLayout(ui->verticalLayout_4);
        ui->verticalLayout_4->addWidget(ui->webView_3);
        ui->webView_3->page()->networkAccessManager()->setCookieJar(ui->webView->page()->networkAccessManager()->cookieJar());
        QNetworkRequest request_3;
        request_3.setUrl(QUrl("http://www.vericon.com.au/auth/mail_login.php"));
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
        request.setUrl(QUrl("http://www.vericon.com.au/logout/"));
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
        request.setUrl(QUrl("http://www.vericon.com.au/auth/mail_login.php"));
        ui->webView_3->load(request);
    }
}

bool MainWindow::download(QNetworkReply *_reply)
{
    reply = _reply;

    QString Name = reply->rawHeader("Content-Disposition")
            .replace("attachment; filename=", "")
            .replace("\"", "");

    Path = QStandardPaths::writableLocation(QStandardPaths::TempLocation) + "/" + Name;

    file = new QFile(Path);
    file->open(QIODevice::WriteOnly);

    downloadDialog = new QDialog(this);
    downloadDialog->setMinimumSize(QSize(360, 70));
    downloadDialog->setMaximumSize(QSize(360, 70));
    downloadDialog->setWindowModality(Qt::ApplicationModal);
    downloadDialog->setWindowTitle("Downloading...");
    gridLayout = new QGridLayout(downloadDialog);
    gridLayout->setObjectName(QString::fromUtf8("gridLayout"));
    gridLayout->setMargin(10);
    label = new QLabel(downloadDialog);
    label->setObjectName(QString::fromUtf8("label"));
    label->setText(Name);
    progressBar = new QProgressBar(downloadDialog);
    progressBar->setValue(1);
    progressBar->setMaximum(100);
    progressBar->setAlignment(Qt::AlignHCenter);
    progressBar->setAlignment(Qt::AlignVCenter);
    gridLayout->addWidget(label, 0, 0, 1, 1);
    gridLayout->addWidget(progressBar, 1, 0, 1, 1);

    connect(reply, SIGNAL(readyRead()), this, SLOT(downloadWrite()));
    connect(reply, SIGNAL(finished()), this, SLOT(downloadFinish()));
    connect(reply, SIGNAL(downloadProgress(qint64,qint64)), this, SLOT(downloadProgress(qint64,qint64)));

    downloadDialog->show();

    return true;
}

void MainWindow::downloadProgress(qint64 bytesReceived, qint64 bytesTotal)
{
    progressBar->setMaximum(bytesTotal);
    progressBar->setValue(bytesReceived);
}

void MainWindow::downloadWrite()
{
    file->write(reply->readAll());
}

void MainWindow::downloadFinish()
{
    downloadWrite();
    file->flush();
    file->close();

    reply->deleteLater();
    reply = NULL;
    delete file;
    file = NULL;

    downloadDialog->close();
    delete downloadDialog;
    downloadDialog = NULL;

    QDesktopServices::openUrl(QUrl(Path));
}

MainWindow::~MainWindow()
{
    delete ui;
}
