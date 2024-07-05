import json
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
import time

# Initialize Chrome options
chrome_options = Options()
chrome_options.add_argument("--disable-notifications")

# Nazwa filmu przekazana jako argument skryptu
nazwa_filmu = "Interstellar"
# URL do wyszukiwania filmu na Filmwebie
search_url = f"https://www.filmweb.pl/search?q={nazwa_filmu}"

# Inicjalizacja przeglądarki
driver = webdriver.Chrome(options=chrome_options)
driver.get(search_url)

# Handle the cookie consent pop-up
wait = WebDriverWait(driver, 20)
try:
    accept_cookies_button = wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, '.didomi-components-button.didomi-button.didomi-dismiss-button.didomi-components-button--color.didomi-button-highlight.highlight-button')))
    accept_cookies_button.click()
    print("Cookies accepted.")
except Exception as e:
    print(f"Cookie consent pop-up not found or already accepted. Error: {e}")

# Poczekaj aż reklama się zakończy (np. 16 sekund)
time.sleep(16)

# Poczekaj aż strona załaduje wyniki wyszukiwania
try:
    first_result = wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, 'div.filmPreview__title a')))
    first_result.click()
    print("First search result clicked.")
except Exception as e:
    print(f"First search result not found or not clickable. Error: {e}")

# Poczekaj aż strona załaduje informacje o filmie
try:
    wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, 'div.filmMainHeader__title')))
    print("Film details page loaded.")
except Exception as e:
    print(f"Film details not found. Error: {e}")

# Pobranie URL do strony z opiniami
film_url = driver.current_url
reviews_url = film_url + "/reviews"
driver.get(reviews_url)

# Poczekaj aż strona załaduje opinie
try:
    wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, 'div.page__container')))
    print("Review page loaded.")
except Exception as e:
    print(f"Review page not found. Error: {e}")

# Pobieranie opinii
reviews = []
review_elements = driver.find_elements(By.CSS_SELECTOR, 'div.page__container')
for review in review_elements:
    try:
        author = review.find_element(By.CSS_SELECTOR, 'span.flatReview__name').text.strip()
        text = review.find_element(By.CSS_SELECTOR, 'div.reviewText').text.strip()
        reviews.append({'author': author, 'text': text})
    except Exception as e:
        print(f"Error processing review: {e}")
        continue

# Zamknięcie przeglądarki
driver.quit()

# Zwrot danych jako JSON
print(json.dumps(reviews, ensure_ascii=False))
