# XML から HTML および PDF への変換

このリポジトリは、WordPressからエクスポートしたWordPress eXtended RSS(WXR)ファイルを HTML ファイルに変換し、その後 PDF ファイルに変換するスクリプトを提供します。ブログデータを XML 形式で保存し、年ごとに分類された読みやすい HTML および PDF ドキュメントを生成することを目的としています。

WordPressからのエクスポート配下を参照してください
[エクスポート &#8211; 日本語サポート](https://wordpress.com/ja/support/export/)


## 目的

このプロジェクトの主な目的は以下の通りです：
1. WordPress の XML エクスポートファイルを年ごとに分類された HTML ファイルに変換する。
2. これらの HTML ファイルをさらに PDF 形式に変換し、配布やアーカイブを容易にする。

## 環境構築

### 前提条件

- PHP 7.0 以上
- Python 3.6 以上
- XML のクリーンアップのための `tidy`
- HTML から PDF への変換のための `WeasyPrint`

### 必要なパッケージのインストール

必要なパッケージがインストールされていることを確認してください。

- `tidy` のインストール

```bash
sudo apt install tidy
```

- `WeasyPrint` のインストール

```bash
pip3 install weasyprint
```

## 使用方法

### 1. リポジトリをクローンする

```bash
git clone https://github.com/yourusername/xml-to-html-pdf.git
cd xml-to-html-pdf
```

### 2. XML ファイルを配置する

WordPress の XML エクスポートファイルの名前を `WordPress.xml` にし、プロジェクトのルートディレクトリに配置してください。

### 3. 変換プロセスを実行する

`make` を使用して変換ステップを簡単に実行できます。

#### 初期化 (過去の出力を削除)

```bash
make init
```

#### XML から HTML への変換

```bash
make html
```

#### HTML から PDF への変換

```bash
make pdf
```

#### すべてのステップを実行

```bash
make all
```

## ディレクトリ構成

- `WordPress.xml`: 入力 XML ファイル
- `convert_xml2html.php`: XML を HTML に変換する PHP スクリプト
- `convert_html2pdf.py`: HTML を PDF に変換する Python スクリプト
- `outputs/html/`: 生成された HTML ファイルのディレクトリ
- `outputs/pdf/`: 生成された PDF ファイルのディレクトリ
- `Makefile`: ステップを自動化するための Makefile

## 注意事項

1. **ファイルエンコーディング**: XML ファイルが UTF-8 でエンコードされていることを確認してください。文字エンコーディングの問題を避けるためです。
2. **依存関係**: 必要な依存関係がすべてインストールされていることを確認してください。
3. **エラーハンドリング**: スクリプトは基本的なエラーシナリオを処理します。変換に失敗した場合は、`error_log.txt` を確認してください。

## 貢献

このリポジトリをフォークし、フィーチャーブランチを作成し、プルリクエストを送信してください。大きな変更の場合は、最初に問題を開いて変更内容を議論してください。

## ライセンス

このプロジェクトは MIT ライセンスの下でライセンスされています。詳細については `LICENSE` ファイルを参照してください。
```
