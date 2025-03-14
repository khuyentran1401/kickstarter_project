# Guessing Game
Test your judgement by deciding whether a project will reach its goal

![](project_cs234.gif)

## Inspiration
We often use machine learning to predict the outcome of data. But how the results would be if we make predictions based on limited information without using machine learning? This project aims to answer that question.


## Background: 
[Kickstarter](https://www.kickstarter.com/) is an American public benefit corporation that maintains a global crowdfunding platform focused on creativity. Backers can fund projects such as films, music, stage shows, comics, journalism, video games, technology, publishing, and food-related projects. 

People who back Kickstarter projects are offered tangible rewards or experiences in exchange for their pledges.

Project creators choose a deadline and a minimum funding goal. If the goal is not met by the deadline, no funds are collected

## Objective: 
A project can be successful or failed. If the project did not reach its goal by the deadline, it is considered a failed project. 

In this game, you will use the information provided about a project to decide whether it will be successful or not. You can look up hints that contain the information and outcome of other projects in order to guess the outcome of your chosen project. 

## Data:
I pulled the data from [Kickstarter Projects dataset](https://www.kaggle.com/kemical/kickstarter-projects) on Kaggle. The dataset is collected from kickstarter on 2018. I removed the rows whose state are not successful or failed.

## Languages used in this project:
* HTML
* CSS
* MySQL
* PHP
* Javascript
* JQuery

## About the files in this project
* `kickstarter.sql`: Database that contains data on the users, kickstarter projets
* `loginForm.php`: Login
* `createAccount.php`: Sign up
* `landing.php`: Introduce the game and ask users to choose a category and a kickstart project
* `game.php`: Show the project information and choose number of hints
* `hint.php`: Show hints and guess whether the projects will be succesful
* `answer.php`: Show result
* `record.php`: Show all trials of that user

## How to run the project
1. Create a database in MySQL to import the data from `kickstarter.sql` 
```bash
mysql -u -username -p password
```
```sql
CREATE DATABASE database_name;
exit;
```
2. Import data from `kickstarter.sql` 
```bash
mysql -u username -p password kickstarter < "kickstarter.sql"
```
3. Use tools such as [MAMP](https://www.mamp.info) to view the website. If you are using VSCode, use PHP Server extension to view the project on the website



