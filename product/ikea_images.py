import os
import json
import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from urllib.parse import urljoin

# 要抓的 IKEA 頁面
url = 'https://www.ikea.com.tw/zh/products/dining-seating/non-upholstered-chairs/skogsbo-art-60546031'

# 建立 Selenium 瀏覽器實例（自動使用 Selenium Manager 抓 driver）
driver = webdriver.Chrome()
driver.get(url)
time.sleep(5)  # 等待頁面載入

# 抓取所有圖片
images = driver.find_elements(By.TAG_NAME, 'img')
results = []

for img in images:
    src = img.get_attribute('src')
    if src and src.startswith('http') and ('.jpg' in src or '.jpeg' in src or '.png' in src):
        full_url = urljoin(url, src)
        results.append({
            "產品頁面": url,
            "圖片網址": full_url
        })

driver.quit()

desktop_path = os.path.join(os.path.expanduser('~'), 'Desktop', 'ikea_images.json')
with open(desktop_path, 'w', encoding='utf-8') as f:
    json.dump(results, f, ensure_ascii=False, indent=2)

print(f"✅ 已儲存 {len(results)} 筆圖片資料到 ikea_images.json")
