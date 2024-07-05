import json
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
import time
import sys

# Initialize Chrome options
chrome_options = Options()
chrome_options.add_argument("--disable-notifications")

# Film title passed as a script argument
nazwa_filmu = sys.argv[1]
# URL for searching the film on Filmweb
search_url = f"https://www.filmweb.pl/search?q={nazwa_filmu}"

# Initialize the browser
driver = webdriver.Chrome(options=chrome_options)
driver.get(search_url)

# Handle the cookie consent pop-up
try:
    # Wait for the accept cookies button and click it
    accept_cookies_button = WebDriverWait(driver, 10).until(
        EC.element_to_be_clickable((By.CSS_SELECTOR, '.didomi-components-button.didomi-button.didomi-dismiss-button.didomi-components-button--color.didomi-button-highlight.highlight-button'))
    )
    accept_cookies_button.click()
    print("Cookies accepted.")
except Exception as e:
    print(f"Cookie consent pop-up not found or already accepted. Error: {e}")

# Wait for any ads to finish (e.g., wait 16 seconds)
time.sleep(16)

# Wait for the search results page to load
try:
    first_result = WebDriverWait(driver, 10).until(
        EC.element_to_be_clickable((By.CSS_SELECTOR, '.preview__link'))
    )
    first_result.click()
    print("First search result clicked.")
except Exception as e:
    print(f"First search result not found or not clickable. Error: {e}")

# Get the URL for the page with reviews
film_url = driver.current_url
reviews_url = film_url + "/reviews"
driver.get(reviews_url)

# Wait a bit for the reviews page to load
time.sleep(5)

# Collect reviews
reviews = []
try:
    review_elements = driver.find_elements(By.CSS_SELECTOR, '.flatReview')
    for review in review_elements:
        try:
            author = review.find_element(By.CSS_SELECTOR, '.flatReview__author').text.strip()
            title = review.find_element(By.CSS_SELECTOR, '.flatReview__title').text.strip().replace('"\"', "")
            text = review.find_element(By.CSS_SELECTOR, '.flatReview__text').text.strip().replace('"\"', "")
            reviews.append({'author': author, 'title': title, 'text': text})
        except Exception as e:
            print(f"Error processing review: {e}")
            continue
except Exception as e:
    print(f"Error retrieving review elements: {e}")

# Close the browser
driver.quit()

# Return data as JSON
print(json.dumps(reviews, ensure_ascii=False, indent=2))
