import requests
from bs4 import BeautifulSoup

url = "https://www.filmweb.pl/ranking/film"
response = requests.get(url)
soup = BeautifulSoup(response.text, "html.parser")

# Znajdujemy wszystkie elementy filmów na stronie
films = soup.find_all("div", class_="rankingType")

film_data = []

for film in films:
    # Pobieramy tytuł filmu
    title_element = film.find("h2", class_="rankingType__title")
    if title_element:
        title = title_element.get_text(strip=True)
    else:
        title = "Brak tytułu"

    # Pobieramy ocenę filmu
    rating_element = film.find("span", class_="rankingType__rate--value")
    if rating_element:
        rating = rating_element.get_text(strip=True)
    else:
        rating = "Brak oceny"

    # Pobieramy liczbę ocen
    quantity_element = film.find("span", class_="rankingType__rate--count")
    if quantity_element:
        # Pobieramy tekst z elementu
        quantity_text = quantity_element.get_text(strip=True)
        # Usuwamy końcówkę "ocen"
        quantity = quantity_text.replace("ocen", "").replace("y", "").strip().replace(" ", "")
    else:
        quantity = "Brak liczby ocen"

    film_data.append({
        "title": title,
        "rating": rating,
        "quantity": quantity
    })

# Wyświetlamy zebrane dane
for film in film_data:
    print(f"Tytuł: {film['title']}, Ocena: {film['rating']}, Liczba ocen: {film['quantity']}")
