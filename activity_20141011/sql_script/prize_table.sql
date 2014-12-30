create database activity_prize character set 'utf8' collate 'utf8_general_ci';

create table activity
(
    activityid int(4) not null auto_increment,
    details varchar(256) default null, -- activity details
    startdate datetime default null, -- activity start
    enddate datetime default null, -- activity end
    prizedescription varchar(256) default null, -- prize details
    vouchervalidtime int(8) default 0, -- voucher valid days 
    voucherexpiredate datetime default null, -- voucher expired date
        -- note: if valid days < 0, then datetime cannot be null.
        -- else if valid days = 0, then there is no expired date at all.
        -- else the expiredate can be either null or not null.
    primary key (activityid)
) default charset=utf8;

create table personinfo
(
    personid int(8) not null auto_increment, -- person ID
    personname varchar(128) not null, -- name of the person
    mobile varchar(32) not null, -- mobile number (need to be checked)
    email varchar(256) not null, -- email address (need to be checked)
    qqnumber varchar(10) not null, -- qqnumber (need to be checked)
    wgateid varchar(128), -- from weixingate.com/gate.php
    create_at datetime default null, -- record creation date
    vouchercode varchar(32) default null, -- voucher code (temporarily unavailable)
    voucherserial int(4) not null, -- activity sequence
    foreign key (voucherserial) references activity(activityid),
    prizedetails tinyint(1) default 0, -- prize -- 1 for $50 (80%), 2 for $100 (10%), 3 for $150 (6%), 4 for $200 (3%), 5 for $250 (1%)
    prizestartdate datetime default null, -- voucher start date
    prizeenddate datetime default null, -- voucher end date (if null, there is no expired time)
    options varchar(128) not null, -- options of consultation
    primary key (personid)
) default charset=utf8;

create index indexpersonname
on personinfo(personname);

create index indexwgateid
on personinfo(wgateid);

create index indexcreateat
on personinfo(create_at);

create index indexvoucherserial
on personinfo(voucherserial);

create index indexprizedetails
on personinfo(prizedetails);

create index indexprizestartdate
on personinfo(prizestartdate);

create index indexprizeenddate
on personinfo(prizeenddate);





  
  
  
  
   