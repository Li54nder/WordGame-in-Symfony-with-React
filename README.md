# WordGame Symfony Project

This is a Symfony & React project for simple “word game” web application.

## Description
The user must be able to enter a word and the application should allow only the words that are in the English dictionary. The application should score the word by the following rules:

1. Give 1 point for each unique letter.
2. Give 3 extra points if the word is a palindrome.
3. Give 2 extra points if the word is “almost palindrome”. Definition of “almost palindrome”: if by removing at most one letter from the word, the word will be a true palindrome.