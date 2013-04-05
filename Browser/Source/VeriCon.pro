#-------------------------------------------------
#
# Project created by QtCreator 2013-03-04T18:32:34
#
#-------------------------------------------------

QT       += core gui webkitwidgets network

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

TARGET = VeriCon
TEMPLATE = app


SOURCES += main.cpp\
        mainwindow.cpp

HEADERS  += mainwindow.h

FORMS    += mainwindow.ui

RC_FILE = vericon.rc

RESOURCES += \
    vericon.qrc

QTPLUGIN += qsvgicon qgif qico qjpeg qmng qsvg qtga qtiff qwbmp
