create database BlogPress_db;
use BlogPress_db;

create table Author(
	Author_id int PRIMARY KEY AUTO_INCREMENT,
	name_author varchar(50),
    email varchar(100),
    password_auth varchar(100)
);

create table Visitor(
    Visit_id int PRIMARY key AUTO_INCREMENT,
    name_visit varchar(50),
    email varchar(100)
);

create table Article(
    id_art int PRIMARY KEY AUTO_INCREMENT,
    title varchar(50),
    content text,
    views int DEFAULT 0,
    likes int DEFAULT 0,
    create_dat datetime DEFAULT CURRENT_TIMESTAMP,
    Author_id int foreign key references Author(Author_id)
);

create table Comment(
    cmt_id int PRIMARY key AUTO_INCREMENT,
    content text,
    create_dat datetime DEFAULT CURRENT_TIMESTAMP,
    id_art int foreign key references Article(id_art),
    Visit_id int foreign key references Visitor(Visit_id),
    Author_id int foreign key references Author(Author_id)
);

create table Stat(
    Stat_id int PRIMARY key AUTO_INCREMENT,
    views int DEFAULT 0,
    likes int DEFAULT 0,
    comments int DEFAULT 0,
    id_art int foreign key references Article(id_art)
);