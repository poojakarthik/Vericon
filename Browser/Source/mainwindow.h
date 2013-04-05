#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include <QtWebKit>
#include <QtNetwork>
#include <QtPlugin>
#include <QFile>
#include <QStandardPaths>
#include <QDialog>
#include <QLabel>
#include <QProgressBar>

namespace Ui {
class MainWindow;
}

class MainWindow : public QMainWindow
{
    Q_OBJECT
    
public:
    explicit MainWindow(QWidget *parent = 0);
    ~MainWindow();
    
private:
    Ui::MainWindow *ui;

protected slots:
    void adjustTitle();
    void checkPage();
    void checkPage_2();
    void checkPage_3();
    bool download(QNetworkReply*reply_);
    void downloadProgress(qint64 bytesReceived, qint64 bytesTotal);
    void downloadWrite();
    void downloadFinish();
};

#endif // MAINWINDOW_H
