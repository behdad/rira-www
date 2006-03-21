package org.rira.search;

/* ====================================================================
 * The Apache Software License, Version 1.1
 *
 * Copyright (c) 2001 The Apache Software Foundation.  All rights
 * reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *
 * 3. The end-user documentation included with the redistribution,
 *    if any, must include the following acknowledgment:
 *       "This product includes software developed by the
 *        Apache Software Foundation (http://www.apache.org/)."
 *    Alternately, this acknowledgment may appear in the software itself,
 *    if and wherever such third-party acknowledgments normally appear.
 *
 * 4. The names "Apache" and "Apache Software Foundation" and
 *    "Apache Lucene" must not be used to endorse or promote products
 *    derived from this software without prior written permission. For
 *    written permission, please contact apache@apache.org.
 *
 * 5. Products derived from this software may not be called "Apache",
 *    "Apache Lucene", nor may "Apache" appear in their name, without
 *    prior written permission of the Apache Software Foundation.
 *
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED.  IN NO EVENT SHALL THE APACHE SOFTWARE FOUNDATION OR
 * ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF
 * USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 * ====================================================================
 *
 * This software consists of voluntary contributions made by many
 * individuals on behalf of the Apache Software Foundation.  For more
 * information on the Apache Software Foundation, please see
 * <http://www.apache.org/>.
 */

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;

import org.apache.lucene.analysis.Analyzer;
import com.cybermehr.lucene.analysis.persian.PersianAnalyzer;
import org.apache.lucene.document.Document;
import org.apache.lucene.search.Searcher;
import org.apache.lucene.search.IndexSearcher;
import org.apache.lucene.search.Query;
import org.apache.lucene.search.BooleanQuery;
import org.apache.lucene.search.Hits;
import com.cybermehr.lucene.queryParser.QueryParser;

class SearchObjects {
  public static void main(String[] args) throws Exception {
      Searcher searcher = new IndexSearcher(args[0]);
      Analyzer analyzer = new PersianAnalyzer();

      BufferedReader in = new BufferedReader(new InputStreamReader(System.in, "UTF-8"));
      OutputStreamWriter out = new OutputStreamWriter(System.out, "UTF-8");
      
      String l1, l2, l3, l4;
      int i = 0;
      while ((l1 = in.readLine()) != null && (l2 = in.readLine()) != null
          && (l3 = in.readLine()) != null && (l4 = in.readLine()) != null) {
	int limit = Integer.parseInt(l1);
	int start = Integer.parseInt(l2);

	Query q = null;
	Query textquery = null;
	Query idnquery = null;

	if (!l3.equals("")) {
	  idnquery = QueryParser.parse(l3, "idn", analyzer);
	  q = textquery;
	}
	if (!l4.equals("")) {
	  textquery = QueryParser.parse(l4, "contents", analyzer);
	  q = textquery;
	}
	if (idnquery != null && textquery != null) {
	  BooleanQuery bQuery = new BooleanQuery();
	  bQuery.add(idnquery, true, false); // REQUIRED
	  bQuery.add(textquery, true, false); // REQUIRED
	  q = bQuery;
	}

	if (textquery != null)
	  out.write(textquery.toString("contents") + "\n");
	else
	  out.write("\n");

	Hits hits = searcher.search(q);
	out.write(hits.length() + "\n");

	int end = Math.min(hits.length(), start + limit);
	out.write(Math.max(0, (end - start)) + "\n");
	
	for (int c = start; c < end; c++) {
	  Document doc = hits.doc(c);
	  String idn = doc.get("idn");
	  String text = doc.get("contents");
          out.write(idn + "\n" + text + "\n");
	}
	out.flush();

	if (++i % 10000 == 0)
	  System.gc();
      }
      searcher.close();

  }
}
