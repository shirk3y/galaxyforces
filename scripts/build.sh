#!/bin/sh

# ===========================================================================
# RELEASE BUILDER
# Author: zoltarx@galaxy.game-host.org
# ===========================================================================

# ---------------------------------------------------------------------------
# Configuration
# ---------------------------------------------------------------------------

TITLE="Galaxy Forces Release Builder"
VERSION="0.4.5"

SRC=galaxy
TMP="/tmp/$USER-galaxy-$$"
DEST=.
RELEASE=current

# ---------------------------------------------------------------------------
# ANSI
# ---------------------------------------------------------------------------

ESC=`printf "\033"`
TAB=`printf "\t"`

ANSI_CYAN="$ESC[0;36m"
ANSI_BLUE="$ESC[0;34m"
ANSI_ORANGE="$ESC[0;33m"
ANSI_YELLOW="$ESC[1;33m"
ANSI_RED="$ESC[1;31m"
ANSI_WHITE="$ESC[1;37m"
ANSI_MAGENTA="$ESC[0;35m"
ANSI_GREEN="$ESC[0;32m"
ANSI_LIGHTGREEN="$ESC[1;32m"
ANSI_DEFAULT="$ESC[0m"

# ---------------------------------------------------------------------------
# Locale
# ---------------------------------------------------------------------------

if [ $LANG = 'pl_PL' ]; then
	LANG_VERSION="Wersja"
	LANG_SYNTAX="Sk³adnia"
	LANG_HELPOPTION="Pokazuje t± informacje"
	LANG_RELEASEOPTION="Nazwa wydania (domy¶lnie: current)"
	LANG_SOURCEOPTION="Katalog ¼ród³owy (drzewo projektu z którego nale¿y budowaæ)"
	LANG_DESTINATIONOPTION="Katalog docelowy"
	LANG_TEMPOPTION="Katalog tymczasowy"
	LANG_VERBOSEOPTION="Tryb gadatliwy"
	LANG_PRETENDOPTION="Wypisz tylko co zostanie wykonane (u¿yj z opcj± --verbose)"
	LANG_TEMP1="Tworzenie katalogu tymczasowego"
	LANG_TEMP2="Usuwanie katalogu tymczasowego"
	LANG_TEMP3="Generowanie drzewa..."
	LANG_COPY1="Kopiowanie plików..."
	LANG_COPY2="Usuwanie plików zapasowych..."
	LANG_COPY3="Zmiana uprawnieñ plików..."
	LANG_COPY4="Przenoszenie obrazów do katalogu docelowego"
	LANG_BUILD1="Tworzenie archiwum"
	LANG_FATAL="Fatalnie"
	LANG_FATAL1="Nie mogê znale¼æ drzewa projektu! Mo¿e potrzebujesz --help?"
else
	LANG_VERSION="Version"
	LANG_SYNTAX="Syntax"
	LANG_HELPOPTION="Shows this screen"
	LANG_RELEASEOPTION="Release name (current is default)"
	LANG_SOURCEOPTION="Source directory (project tree to build from)"
	LANG_DESTINATIONOPTION="Destination directory"
	LANG_TEMPOPTION="Specify temporary directory"
	LANG_VERBOSEOPTION="Be verbose"
	LANG_PRETENDOPTION="Pretend only (should be used with --verbose option)"
	LANG_TEMP1="Creating temporary directory"
	LANG_TEMP2="Removing temporary directory"
	LANG_TEMP3="Creating initial tree..."
	LANG_COPY1="Copying files..."
	LANG_COPY2="Removing backup files..."
	LANG_COPY3="Setting file permissions..."
	LANG_COPY4="Moving images to destination directory"
	LANG_BUILD1="Building archive"
	LANG_FATAL="Fatal"
	LANG_FATAL1="Can't find project tree! Maybe need --help?"
fi


# ---------------------------------------------------------------------------
# Arguments
# ---------------------------------------------------------------------------

if [ $# -gt 0 ]; then
	while [ $# -gt 0 ]; do
		case "$1" in
		"--destination")
			shift 1
			DEST=$1
		;;
		"--release")
			shift 1
			RELEASE=$1
		;;
		"--source")
			shift 1
			SRC=$1
		;;
		"--temp")
			shift 1
			TMP=$1
		;;
		"--verbose")
			VERBOSE_OPTION=1
		;;
		"--help")
			HELP_OPTION=1
		;;
		esac
		shift 1
	done
fi

# ---------------------------------------------------------------------------
# HELP
# ---------------------------------------------------------------------------

if [ $HELP_OPTION ]; then
	echo "${ANSI_WHITE}${TITLE}${ANSI_DEFAULT} $LANG_VERSION: ${ANSI_LIGHTGREEN}${VERSION}${ANSI_DEFAULT}"
	echo "${LANG_SYNTAX}:"
	echo "${TAB}${ANSI_LIGHTGREEN}--release NAME${ANSI_DEFAULT}       ${LANG_RELEASEOPTION}"
	echo "${TAB}${ANSI_LIGHTGREEN}--source DIR${ANSI_DEFAULT}         ${LANG_SOURCEOPTION}"
	echo "${TAB}${ANSI_LIGHTGREEN}--destination DIR${ANSI_DEFAULT}    ${LANG_DESTINATIONOPTION}"
	echo "${TAB}${ANSI_LIGHTGREEN}--temp DIR${ANSI_DEFAULT}           ${LANG_TEMPOPTION}"
	echo "${TAB}${ANSI_LIGHTGREEN}--verbose${ANSI_DEFAULT}            ${LANG_VERBOSEOPTION}"
	echo "${TAB}${ANSI_LIGHTGREEN}--pretend${ANSI_DEFAULT}            ${LANG_PRETENDOPTION}"
	echo "${TAB}${ANSI_LIGHTGREEN}--help${ANSI_DEFAULT}               ${LANG_HELPOPTION}"
	exit 0
fi

# ---------------------------------------------------------------------------
# Build release
# ---------------------------------------------------------------------------

if [ ! -d $SRC/include -o  ! -d $SRC/locale ]; then
	echo "${ANSI_RED}$LANG_FATAL:${ANSI_DEFAULT} $LANG_FATAL1"
	exit 0
fi

if [ $VERBOSE_OPTION ]; then
	OPT=-v
	OPT1=v
	echo "${ANSI_YELLOW}${LANG_TEMP1} ${ANSI_WHITE}${TMP}${ANSI_DEFAULT}"
fi
mkdir $TMP

if [ $VERBOSE_OPTION ]; then
	echo "${ANSI_YELLOW}${LANG_TEMP3}${ANSI_DEFAULT}"
fi

if [ ! $PRETEND_OPTION ]; then
	mkdir -p $TMP/galaxy $TMP/galaxy/forum $TMP/galaxy/log $TMP/galaxy/doc $TMP/galaxy/style
	mkdir -p $TMP/artwork/galaxy
	mkdir -p $TMP/documentation/galaxy/doc
fi

if [ $VERBOSE_OPTION ]; then
	echo "${ANSI_YELLOW}${LANG_COPY1}${ANSI_DEFAULT}"
fi

if [ ! $PRETEND_OPTION ]; then
	cp $OPT $SRC/* $TMP/galaxy &> /dev/null
	cp -R $OPT $SRC/db $TMP/galaxy
	cp -R $OPT $SRC/include $TMP/galaxy
	rm $TMP/galaxy/include/config.php $TMP/galaxy/include/config.php~ &> /dev/null
	cp -R $OPT $SRC/galaxy $TMP/galaxy
	cp -R $OPT $SRC/js $TMP/galaxy
	cp -R $OPT $SRC/locale $TMP/galaxy
	cp -R $OPT $SRC/sql $TMP/galaxy
	cp -R $OPT $SRC/tools $TMP/galaxy
	cp -R $OPT $SRC/scripts $TMP/galaxy
	cp -R $OPT $SRC/modules $TMP/galaxy
	cp -R $OPT $SRC/style/default $TMP/galaxy/style

	cp $OPT $SRC/doc/* $TMP/documentation/galaxy/doc &> /dev/null

	cp -R $OPT $SRC/flash $TMP/artwork/galaxy
	cp -R $OPT $SRC/images $TMP/artwork/galaxy
	cp -R $OPT $SRC/movies $TMP/artwork/galaxy
	cp -R $OPT $SRC/sounds $TMP/artwork/galaxy
	cp -R $OPT $SRC/gallery $TMP/artwork/galaxy
	cp -R $OPT $SRC/propaganda $TMP/artwork/galaxy
	cp -R $OPT $SRC/style $TMP/artwork/galaxy
fi

if [ $VERBOSE_OPTION ]; then
	echo "${ANSI_YELLOW}${LANG_COPY2}${ANSI_DEFAULT}"
	find "$TMP/galaxy/" -name "*~"
fi

if [ ! $PRETEND_OPTION ]; then
	rm `find "$TMP/galaxy/" -name "*~"`
fi

if [ $VERBOSE_OPTION ]; then
	echo "${ANSI_YELLOW}${LANG_COPY3}${ANSI_DEFAULT}"
	find "$TMP/" -type f
	echo "$TMP/galaxy/log" 
fi

if [ ! $PRETEND_OPTION ]; then
	chmod 644 `find "$TMP/" -type f`
	chmod 777 "$TMP/galaxy/log"
fi

if [ $VERBOSE_OPTION ]; then
	echo "${ANSI_YELLOW}${LANG_COPY2}${ANSI_DEFAULT}"
fi

OLD=`pwd`
cd $TMP

if [ $VERBOSE_OPTION ]; then
	tar cjvf galaxy-$RELEASE.tar.bz2 galaxy
	cd artwork
	tar cjvf ../artwork-$RELEASE.tar.bz2 galaxy
	cd ../documentation
	tar cjvf ../documentation-$RELEASE.tar.bz2 galaxy
else
	tar cjf galaxy-$RELEASE.tar.bz2 galaxy
	cd artwork
	tar cjf ../artwork-$RELEASE.tar.bz2 galaxy
	cd ../documentation
	tar cjf ../documentation-$RELEASE.tar.bz2 galaxy
fi

cd $OLD

if [ $VERBOSE_OPTION ]; then
	echo "${ANSI_YELLOW}${LANG_COPY4} ${ANSI_WHITE}${DEST}${ANSI_DEFAULT}"
fi

mv $OPT $TMP/*bz2 $DEST

# ---------------------------------------------------------------------------
# Finish
# ---------------------------------------------------------------------------

if [ $VERBOSE_OPTION ]; then
	echo "${ANSI_YELLOW}${LANG_TEMP2} ${ANSI_WHITE}${TMP}${ANSI_DEFAULT}"
fi

rm -rf $TMP
