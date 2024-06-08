import os
from weasyprint import HTML

# 入力ディレクトリと出力ディレクトリ
input_dir = 'outputs/html'
output_dir = 'outputs/pdf'

# 出力ディレクトリの作成
os.makedirs(output_dir, exist_ok=True)

# HTMLファイルを一括でPDFに変換
for html_file in os.listdir(input_dir):
    if html_file.endswith('.html'):
        input_path = os.path.join(input_dir, html_file)
        output_path = os.path.join(output_dir, f"{os.path.splitext(html_file)[0]}.pdf")
        HTML(input_path).write_pdf(output_path)
