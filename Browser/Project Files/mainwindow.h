#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include <QtWebKit>
#include <QWebView>

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
};

class webPage : public QWebPage {

public:
    webPage(){QWebPage::QWebPage();}
    QString userAgentForUrl(const QUrl &url ) const {
        QString temp_ua = "AppleWebKit/";
        temp_ua.append(qWebKitVersion());
        temp_ua.append(" (KHTML, like Gecko) Firefox/18.0");
        return temp_ua;
    }
};

#endif // MAINWINDOW_H
