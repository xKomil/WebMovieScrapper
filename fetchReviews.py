import sys
import requests
from bs4 import BeautifulSoup
import json

# Nazwa filmu przekazana jako argument skryptu
nazwa_filmu = sys.argv[1]

# URL do wyszukiwania filmu na Filmwebie
search_url = f"https://www.filmweb.pl/search#/?query={nazwa_filmu}"

search_response = requests.get(search_url)
search_soup = BeautifulSoup(search_response.text, 'html.parser')

# Znalezienie pierwszego wyniku wyszukiwania
first_result = search_soup.select_one('div.hitDesc > a[href]')
if first_result:
    film_url = "https://www.filmweb.pl" + first_result['href']

    # Pobranie strony z opiniami dla znalezionego filmu
    reviews_response = requests.get(film_url + "/reviews")
    reviews_soup = BeautifulSoup(reviews_response.text, 'html.parser')

    # Pobieranie opinii
    reviews = []
    for review in reviews_soup.find_all('div', class_='userReview'):
        author = review.find('span', class_='authorName').text.strip()
        text = review.find('div', class_='reviewText').text.strip()
        reviews.append({'author': author, 'text': text})

    # Zwrot danych jako JSON
    print(json.dumps(reviews, ensure_ascii=False))
else:
    print(json.dumps([]))
