# TP3 - Sécurité et authentification Symfony

## Question 1 : Qu'est-ce que Password Hasher ? Pourquoi utiliser l'algorithme auto ?

Password Hasher is a Symfony service that hashes user passwords before storing them in the database. The auto algorithm allows Symfony to choose a secure hashing algorithm depending on the environment and available configuration.

## Question 2 : Qu'est-ce qu'un User Provider ?

A User Provider is responsible for loading users from a data source, such as a database. Symfony uses it during authentication to retrieve the user using their email address.

## Question 3 : Pourquoi utiliser plainPassword au lieu de password ?

plainPassword is a temporary form field used to receive the password during registration. Symfony hashes it before saving it. Storing a password in plain text is dangerous because anyone accessing the database could read users' passwords.

## Question 4 : Qu'est-ce que l'objet app dans Twig ? D'où vient app.user ?

The app object is a global Twig variable provided by Symfony. It gives access to application information, including the currently authenticated user through app.user. If nobody is logged in, app.user is null.

## Question 5 : Quelle est la différence entre Authentication et Authorization ?

Authentication verifies the identity of a user (who are you?). Authorization determines what an authenticated user is allowed to do (what can you access?).

## Question 6 : Qu'est-ce que Role Hierarchy ?

Role Hierarchy allows one role to inherit the permissions of another role. In this project, ROLE_ADMIN inherits ROLE_USER, so administrators also have the permissions of regular users.
