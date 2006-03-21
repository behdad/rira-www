JAVAS = $(wildcard *.java)
CLASSES = $(subst .java,.class,$(JAVAS))

all: $(CLASSES)

%.class: %.java
	../../../../classpath javac $<

clean:
	$(RM) *.class
