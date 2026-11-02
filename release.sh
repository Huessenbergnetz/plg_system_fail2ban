#!/bin/bash
INFOFILE="hbnfail2ban.xml"
PKGNAME=plg_system_fail2ban

# get current version
VERSION=`grep "<version>" $INFOFILE | sed 's|.*<version>\([0-9\.]*\)</version>.*|\1|'`

TARBALL="${PKGNAME}-${VERSION}.tar.gz"

# remove previous package
if [ -f $TARBALL ]; then
    rm $TARBALL
fi

tar -c -z -f $TARBALL --exclude-vcs \
    language \
    services \
    src \
    hbnfail2ban.xml
