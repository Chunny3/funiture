import requests
from bs4 import BeautifulSoup

url = 'https://www.ikea.com.tw/zh/products/dining-seating/non-upholstered-chairs/skogsbo-art-60546031'

response = requests.get(url)
soup = BeautifulSoup(response.text, 'html.parser')

print(soup.prettify()[:1000])  # 印出抓到的 HTML 前 1000 字元
