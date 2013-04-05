#include "mainwindow.h"
#include <QApplication>

int main(int argc, char *argv[])
{
    QApplication a(argc, argv);
    a.setApplicationName("VeriCon");
    a.setApplicationVersion("2.0.0");
    MainWindow w;
    w.showMaximized();
    
    return a.exec();
}
