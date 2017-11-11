drop table shop;
create table shop (serial serial, name varchar(100), imagename varchar(200), eatDate Date, deadline Datetime, PersonInCharge varchar(100), Date Datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);
insert into shop (name, imagename, eatDate, deadline, PersonInCharge) values ('Drinks', '1.png', '20171110', '20171109120000', 'Bruce');
drop table foodorder;
create table foodorder (orderserial serial,shopserial int, name varchar(50), dish varchar(50), amount int, totalprice int, time datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);
insert into foodorder (shopserial, name, dish, amount, totalprice) values (2, 'Woody', '漢堡', 1, 100);
insert into foodorder (shopserial, name, dish, amount, totalprice) values (2, 'Bruce', '雞翅', 2, 80);