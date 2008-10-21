<?php

include_once "Zend/Search/Lucene.php";

class luceneindex_backend {

  var $has_schema;

  function luceneindex_backend ($silent = false, $create = false) {
    global $cfg;

    $this->debug = $cfg['debug'];
    try {
      if ($create)
        $index = Zend_Search_Lucene::create($cfg['luceneindex']);
      else
        $index = Zend_Search_Lucene::open($cfg['luceneindex']);
    } catch (Zend_Search_Lucene_Exception $e) {
      $this->index = null;
      if ($silent)
        return;
      else
        die ("opening lucene index failed: ".$e->getMessage());
    }

    $this->index = $index;

    Zend_Search_Lucene::setDefaultSearchField('contents');
    Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
    Zend_Search_Lucene_Search_QueryParser::setDefaultOperator(Zend_Search_Lucene_Search_QueryParser::B_AND);
    Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num());
  }

  function query($text='', $idn='', $limit=0) {
    $q = null;
    $textquery = null;
    $idnquery = null;

    if ($idn) {
      $idnquery = Zend_Search_Lucene_Search_QueryParser::parse("idn:($idn)");
      $q = $idnquery;
    }
    if ($text) {
      $textquery = Zend_Search_Lucene_Search_QueryParser::parse($text);
      $q = $textquery;
    }
    if ($idnquery != null && $textquery != null) {
      $q = new Zend_Search_Lucene_Search_Query_Boolean();
      $q->addSubquery($idnquery, true /* required */);
      $q->addSubquery($textquery, true /* required */);
    }

    Zend_Search_Lucene::setResultSetLimit($limit);
    return $this->index->find ($q);
  }

  function optimize() {
    return $this->index->optimize();
  }

  function commit() {
    return $this->index->commit();
  }

  function add_document($idn, $text) {
    $doc = new Zend_Search_Lucene_Document();
    $doc->addField(Zend_Search_Lucene_Field::Text('idn', "x $idn x", 'utf-8'));
    $doc->addField(Zend_Search_Lucene_Field::Text('contents', $text, 'utf-8'));
    $this->index->addDocument($doc);
  }
}

class luceneindex_backend_factory {
  static function &get_luceneindex ($create = false) {
    static $luceneindex = NULL;
    if (!$luceneindex) {
      $luceneindex = new luceneindex_backend(true, $create);
      if (!$luceneindex->index) {
        echo "Failed opening lucene index\n";
        $luceneindex = NULL;
      }
    }
    return $luceneindex;
  }
}

?>
