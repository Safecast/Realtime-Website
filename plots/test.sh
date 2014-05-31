
CONFIG := /var/www/plots/Makefile.config
include $(CONFIG)

cache:	$(LIVE_SENSORS:%=/var/www/feeds/device_%.csv)

cache/%.csv:
	@echo "Fetching data to fill $@ ..."
