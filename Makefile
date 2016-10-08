.PHONY: test

all: epass_home.tar.gz

dist: test epass_home.tar.gz

test:
	cd test; ./test.php

epass_home.tar:
	tar --directory public_html -cvf epass_home.tar epass_home favicon.ico epass.php

epass_home.tar.gz: epass_home.tar
	gzip epass_home.tar

clean:
	$(RM) epass_home.tar.gz epass_home.tar
