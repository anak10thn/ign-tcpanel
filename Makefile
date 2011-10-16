#       This program is free software; you can redistribute it and/or modify
#       it under the terms of the GNU General Public License as published by
#       the Free Software Foundation; either version 2 of the License, or
#       (at your option) any later version.
#       
#       This program is distributed in the hope that it will be useful,
#       but WITHOUT ANY WARRANTY; without even the implied warranty of
#       MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#       GNU General Public License for more details.
#       
#       You should have received a copy of the GNU General Public License
#       along with this program; if not, write to the Free Software
#       Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
#       MA 02110-1301, USA.
# crated by ibnu yahya <ibnu.yahya@toroo.org>

PREFIX = /usr
INSTALL_LOG = install.log
VERSION = 1.0.1
.PHONY : install
.PHONY : uninstall
.PHONY : toroobuild

all:
	@echo "Makefile: Available actions: install, uninstall,toroobuild"
install:
	-mkdir -p $(PREFIX)/share/tcpanel
	@echo "Created tcpanel directori"
	-cp -R -L src/* $(PREFIX)/share/tcpanel
	@echo "Installed source code to prefix directori"
	-cp -R -L src/launcher/tcpanel.desktop $(PREFIX)/share/applications/
	@echo "Installed launcher"
	-cp -R -L src/bin/tcpanel $(PREFIX)/sbin/
	@echo "Makefile: Toroo Control Panel installed."
uninstall:
	rm -rf $(PREFIX)/share/tcpanel
	rm -rf $(PREFIX)/share/applications/tcpanel.desktop
	rm -rf $(PREFIX)/sbin/tcpanel
toroobuild:
	chmod +X Build
	bash Build
	echo "Makefile: Toroo Control Panel package has been build."
