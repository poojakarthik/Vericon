#include <QtGui/QApplication>
#include "mainwindow.h"

int main(int argc, char *argv[])
{
    QApplication a(argc, argv);
    a.setApplicationName(QString("VeriCon"));
    a.setApplicationVersion(QString("2.0.0"));
    MainWindow w;
    w.showMaximized();

    return a.exec();
}
