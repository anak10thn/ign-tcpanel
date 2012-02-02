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

INSTALL_LOG = install.log
VERSION = 1.0.1
.PHONY : install
.PHONY : uninstall
.PHONY : ignbuild

all:
	@echo "Makefile: Available actions: install, uninstall,toroobuild"
install:
	-install -d -m 755 $(PREFIX)/usr/share/ign-control-panel
	-install -d -m 755 $(PREFIX)/usr/share/applications
	-install -d -m 755 $(PREFIX)/usr/sbin
	@echo "Created directori"
	-cp -R -L src/* $(PREFIX)/usr/share/ign-control-panel
	@echo "Installed source code to prefix directori"
	-cp -R -L src/launcher/ign-control-panel.desktop $(PREFIX)/usr/share/applications/
	@echo "Installed launcher"
	-cp -R -L src/bin/ign-control-panel $(PREFIX)/usr/sbin/
	@echo "Makefile: IGN Control Panel installed."
uninstall:
	rm -rf $(PREFIX)/share/tcpanel
	rm -rf $(PREFIX)/share/applications/tcpanel.desktop
	rm -rf $(PREFIX)/sbin/tcpanel
