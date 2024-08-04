/**
 * @param {*} formId
 * @author Gabi
 * @author Alex
 * @description - main.js - Funções JS usadas no BackEnd 
 */

function toggleFilterForm(formId) {
    var form = document.getElementById(formId);
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const searchIcon = document.getElementById("searchIcon");
    const searchBar = document.getElementById("searchBar");
    const searchBox = document.getElementById("searchBoxInput");

    searchBar.style.display = 'none';

    if (searchIcon && searchBar) {
        searchIcon.addEventListener("click", function() {
            if (searchBar.style.display === 'none' || searchBar.style.display === '') {
                searchBar.style.display = 'block';
                searchBox.focus(); 
            } else {
                searchBar.style.display = 'none';
            }
        });
    } else {
        console.log("searchIcon or searchBar not found");
    }

    if (searchBox) {
        searchBox.addEventListener("keyup", function(event) {
            if (event.key === "Enter") {
                const searchTerm = searchBox.value.trim();
                if (searchTerm !== "") {
                    window.location.href = `../Controller/searchProduct.php?search=${encodeURIComponent(searchTerm)}`;
                }
            }
        });
    } else {
        console.log("searchBox not found");
    }
});


