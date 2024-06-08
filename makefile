# Makefile for converting XML to HTML and then to PDF

# 変数定義
XML_FILE = WordPress.xml
HTML_DIR = outputs/html
PDF_DIR = outputs/pdf

# 初期化処理
init:
	# 出力ディレクトリが存在しない場合は作成
	mkdir -p $(HTML_DIR) $(PDF_DIR)
	# 過去に生成されたHTMLファイルとPDFファイルを削除
	rm -f $(HTML_DIR)/*.html $(PDF_DIR)/*.pdf

# HTMLファイルの生成
html: init
	php convert_xml2html.php $(XML_FILE)

# PDFファイルの生成
pdf: html
	python3 convert_html2pdf.py

# すべてを実行
all: init html pdf

# 便利なメッセージ表示
.PHONY: init html pdf all
