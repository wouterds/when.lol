all: tag

PWD = $(shell pwd)

clean:
	-rm -rf $(PWD)/node_modules
	-rm -rf $(PWD)/package-lock.json
	-rm -rf $(PWD)/vendor
