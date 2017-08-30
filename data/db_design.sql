SELECT DISTINCT
  complaint,
  COUNT(complaint)
FROM
  `service_order`
WHERE
  1
GROUP BY
  complaint
 ORDER BY
 	complaint

select cluster_code, COUNT(cluster_code) as count from clusters
group by cluster_code
    Union
select 'TOTAL' cluster_code, COUNT(cluster_code)
from clusters


SELECT clr.cluster_code, 
SUM(CASE WHEN so.complaint_type = "hardware" THEN 1 ELSE 0 END) as no_of_reports_hardware, 
SUM(CASE WHEN so.complaint_type = "software" THEN 1 ELSE 0 END) as no_of_reports_software,
COUNT(so.complaint_type) as total_reports_per_cluster
FROM clusters clr
LEFT JOIN computer_designation cd on cd.designation = clr.cluster_code
LEFT JOIN computers c on c.computer_id = cd.computer_id
LEFT JOIN service_order so on so.computer_name = c.computer_name
GROUP BY clr.cluster_code
UNION
SELECT 'OVERALL TOTAL' cluster_code, 
SUM(CASE WHEN so.complaint_type = "hardware" THEN 1 ELSE 0 END), 
SUM(CASE WHEN so.complaint_type = "software" THEN 1 ELSE 0 END),
COUNT(so.complaint_type)
FROM clusters clr
LEFT JOIN computer_designation cd on cd.designation = clr.cluster_code
LEFT JOIN computers c on c.computer_id = cd.computer_id
LEFT JOIN service_order so on so.computer_name = c.computer_name







SELECT r.room_no, 
SUM(CASE WHEN so.complaint_type = "hardware" THEN 1 ELSE 0 END) as no_of_reports_hardware, 
SUM(CASE WHEN so.complaint_type = "software" THEN 1 ELSE 0 END) as no_of_reports_software,
COUNT(so.complaint_type) as total_reports_per_classroom
from classrooms cl
left join rooms r on r.room_id = cl.room_id
left join computer_designation cd on cd.designation = r.room_no
left join computers c on c.computer_id = cd.computer_id
left join service_order so on so.computer_name = c.computer_name
group by r.room_no



UPDATE
  `computer_designation`
SET
  `designation_type` = 'office'
WHERE
  designation = 'TSG' OR designation = 'CCSFAC'
  OR designation = 'CONFAC'
  OR designation = 'SMDTC'
  OR designation = 'EL'
  OR designation = 'ISL'
  OR designation = 'SAO';

UPDATE
  `computer_designation`
SET
  `designation_type` = 'laboratory'
WHERE
  designation = 'ST101' OR designation = 'ST102'
  OR designation = 'ST103' OR designation = 'ST104' OR designation = 'ST105'
  OR designation = 'ST201' OR designation = 'ST202' OR designation = 'ST203'
  OR designation = 'ST204' OR designation = 'ST205' OR designation = 'ST305';

UPDATE `service_order_completion` SET `unit_status`='under repair' WHERE unit_status = '';

UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '2';
UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '3';
UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '4';
UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '5';
UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '6';
UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '7';
UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '8';
UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '9';
UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '10';
UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '11';
UPDATE `computer_designation` SET `designation_type`='laboratory' WHERE computer_id = '12010';


SELECT *
FROM computers c
INNER JOIN computer_designation cd on c.computer_id = cd.computer_id
LEFT JOIN clusters clr on cd.designation = clr.cluster_code
WHERE cd.designation_type = 'laboratory' and cd.designation_type = 'lecture'

SELECT *
FROM computers c
INNER JOIN computer_designation cd on c.computer_id = cd.computer_id
LEFT JOIN clusters clr on cd.designation = clr.cluster_code
WHERE (cd.designation_type = 'laboratory'
OR cd.designation_type = 'lecture')
OR clr.cluster_id = '5'


SELECT `c`.`computer_id`, `c`.`computer_name`
FROM `computers` `c`
INNER JOIN `computer_designation` `cd` ON `c`.`computer_id` = `cd`.`computer_id`
LEFT JOIN `clusters` `clr` ON `cd`.`designation` = `clr`.`cluster_code`
WHERE (`cd`.`designation_type` = 'laboratory'
OR `cd`.`designation_type` = 'lecture')
AND `clr`.`cluster_id` = '5'
ORDER BY `computer_name`;

SELECT `c`.`computer_id`, `c`.`computer_name`
FROM `computers` `c`
INNER JOIN `computer_designation` `cd` ON `c`.`computer_id` = `cd`.`computer_id`
LEFT JOIN `clusters` `clr` ON `cd`.`designation` = `clr`.`cluster_code`
WHERE `clr`.`cluster_id` = '5'
OR `cd`.`designation_type` = 'lecture'
OR `cd`.`designation_type` = 'laboratory'
ORDER BY `computer_name`


##########################

SELECT soa.date_reported,
		SUM(CASE WHEN so.complaint_type = 'hardware' THEN 1 ELSE 0 END) as no_of_reports_hardware,
        SUM(CASE WHEN so.complaint_type = 'software' THEN 1 ELSE 0 END) as no_of_reports_software
FROM service_order so
INNER JOiN service_order_acceptance soa on so.ref_no = soa.ref_no
GROUP by soa.date_reported
ORDER by soa.date_reported desc


SELECT sdbd.month_reported,
		SUM(CASE WHEN so.complaint_type = 'hardware' THEN 1 ELSE 0 END) as no_of_reports_hardware,
        SUM(CASE WHEN so.complaint_type = 'software' THEN 1 ELSE 0 END) as no_of_reports_software
FROM service_order so
INNER JOiN service_order_acceptance soa on so.ref_no = soa.ref_no
INNER JOIN soa_date_break_down sdbd on soa.ref_no = sdbd.ref_no
GROUP by sdbd.month_reported
ORDER by sdbd.month_reported desc


SELECT so.room_no, COUNT(*) as no_of_reports
FROM service_order so
LEFT JOIN computers c on so.computer_name = c.computer_name
INNER JOIN computer_designation cd on c.computer_id = cd.computer_id
LEFT JOIN rooms r on cd.room_no = r.room_no
INNER JOIN classrooms clr on r.room_id = clr.room_id
GROUP by r.room_no


SELECT  @RankRow := @RankRow+ 1 AS Rank, c.computer_name
FROM    computers c
JOIN    (SELECT @RankRow := 0) r;

create table service_order (
	ref_no int not null auto_increment,
	emp_ID varchar (255) not null,
	emp_name varchar (255) not null,
	department int not null,
	position varchar  (255) not null,
	contact_no varchar  (255) not null,
	complaint_type varchar (255) not null,
	complaint_details varchar (255) not null,
	resource_name varchar (255) not null,
	if_pulled_out boolean not null,
	primary key (ref_no)
);

create table service_order_acceptance (
	ref_no int not null,
	received_by int not null,
	assigned_to int (255) not null,
	suggested_action varchar (255) not null,
	date_reported varchar (255) not null,
	time_reported varchar (255) not null,
	primary key (ref_no),
	foreign key (ref_no) references service_order (ref_no)
	ON DELETE CASCADE
);

CREATE TABLE soa_date_break_down(
	ref_no int not null primary key,
    month_reported varchar(255) not null,
    year_reported varchar(255) not null,
    FOREIGN key (ref_no) REFERENCES service_order_acceptance(ref_no)
    on DELETE CASCADE
);

create table service_order_completion (
	ref_no int not null,
	action_taken varchar (255) not null,
	date_finished varchar (255) not null,
	time_finished varchar (255) not null,
	primary key (ref_no),
	foreign key (ref_no) references service_order (ref_no)
	ON DELETE CASCADE
);



create table computer(
	computer_id int auto_increment not null primary key,
	computer_type varchar(255) not null,
	brand_clone_name varchar(255) not null
	
);

create table computer_designation(
	computer_id int not null primary key,
	department_id int not null,
	assigned_to varchar(255) not null,
	date_assigned varchar(255) not null,
	foreign key (computer_id) references computer(computer_id)
	on delete cascade
);

create table laboratories (
	room_id int not null auto_increment primary key,
	resource_name varchar (255) not null,
	foreign key (computer_id) references computer(computer_id)
	on delete cascade
);

############# update of computer name ################
UPDATE `computers` SET `computer_name`='L21WS00' WHERE computer_name = 'L201WS00';
UPDATE `computers` SET `computer_name`='L21WS01' WHERE computer_name = 'L201WS01';
UPDATE `computers` SET `computer_name`='L21WS02' WHERE computer_name = 'L201WS02';
UPDATE `computers` SET `computer_name`='L21WS03' WHERE computer_name = 'L201WS03';
UPDATE `computers` SET `computer_name`='L21WS04' WHERE computer_name = 'L201WS04';
UPDATE `computers` SET `computer_name`='L21WS05' WHERE computer_name = 'L201WS05';
UPDATE `computers` SET `computer_name`='L21WS06' WHERE computer_name = 'L201WS06';
UPDATE `computers` SET `computer_name`='L21WS07' WHERE computer_name = 'L201WS07';
UPDATE `computers` SET `computer_name`='L21WS08' WHERE computer_name = 'L201WS08';
UPDATE `computers` SET `computer_name`='L21WS09' WHERE computer_name = 'L201WS09';
UPDATE `computers` SET `computer_name`='L21WS10' WHERE computer_name = 'L201WS10';
UPDATE `computers` SET `computer_name`='L21WS11' WHERE computer_name = 'L201WS11';
UPDATE `computers` SET `computer_name`='L21WS12' WHERE computer_name = 'L201WS12';
UPDATE `computers` SET `computer_name`='L21WS13' WHERE computer_name = 'L201WS13';
UPDATE `computers` SET `computer_name`='L21WS14' WHERE computer_name = 'L201WS14';
UPDATE `computers` SET `computer_name`='L21WS15' WHERE computer_name = 'L201WS15';
UPDATE `computers` SET `computer_name`='L21WS16' WHERE computer_name = 'L201WS16';
UPDATE `computers` SET `computer_name`='L21WS17' WHERE computer_name = 'L201WS17';
UPDATE `computers` SET `computer_name`='L21WS18' WHERE computer_name = 'L201WS18';
UPDATE `computers` SET `computer_name`='L21WS19' WHERE computer_name = 'L201WS19';
UPDATE `computers` SET `computer_name`='L21WS20' WHERE computer_name = 'L201WS20';
UPDATE `computers` SET `computer_name`='L21WS21' WHERE computer_name = 'L201WS21';
UPDATE `computers` SET `computer_name`='L21WS22' WHERE computer_name = 'L201WS22';
UPDATE `computers` SET `computer_name`='L21WS23' WHERE computer_name = 'L201WS23';
UPDATE `computers` SET `computer_name`='L21WS24' WHERE computer_name = 'L201WS24';
UPDATE `computers` SET `computer_name`='L21WS25' WHERE computer_name = 'L201WS25';
UPDATE `computers` SET `computer_name`='L21WS26' WHERE computer_name = 'L201WS26';
UPDATE `computers` SET `computer_name`='L21WS27' WHERE computer_name = 'L201WS27';
UPDATE `computers` SET `computer_name`='L21WS28' WHERE computer_name = 'L201WS28';
UPDATE `computers` SET `computer_name`='L21WS29' WHERE computer_name = 'L201WS29';
UPDATE `computers` SET `computer_name`='L21WS30' WHERE computer_name = 'L201WS30';
UPDATE `computers` SET `computer_name`='L21WS31' WHERE computer_name = 'L201WS31';
UPDATE `computers` SET `computer_name`='L21WS32' WHERE computer_name = 'L201WS32';
UPDATE `computers` SET `computer_name`='L21WS33' WHERE computer_name = 'L201WS33';
UPDATE `computers` SET `computer_name`='L21WS34' WHERE computer_name = 'L201WS34';
UPDATE `computers` SET `computer_name`='L21WS35' WHERE computer_name = 'L201WS35';
UPDATE `computers` SET `computer_name`='L21WS36' WHERE computer_name = 'L201WS36';
UPDATE `computers` SET `computer_name`='L21WS37' WHERE computer_name = 'L201WS37';
UPDATE `computers` SET `computer_name`='L21WS38' WHERE computer_name = 'L201WS38';
UPDATE `computers` SET `computer_name`='L21WS39' WHERE computer_name = 'L201WS39';
UPDATE `computers` SET `computer_name`='L21WS40' WHERE computer_name = 'L201WS40';
UPDATE `computers` SET `computer_name`='L21WS41' WHERE computer_name = 'L201WS41';
UPDATE `computers` SET `computer_name`='L21WS42' WHERE computer_name = 'L201WS42';
UPDATE `computers` SET `computer_name`='L21WS43' WHERE computer_name = 'L201WS43';
UPDATE `computers` SET `computer_name`='L21WS44' WHERE computer_name = 'L201WS44';
UPDATE `computers` SET `computer_name`='L21WS45' WHERE computer_name = 'L201WS45';
UPDATE `computers` SET `computer_name`='L21WS46' WHERE computer_name = 'L201WS46';
UPDATE `computers` SET `computer_name`='L21WS47' WHERE computer_name = 'L201WS47';
UPDATE `computers` SET `computer_name`='L21WS48' WHERE computer_name = 'L201WS48';

UPDATE `computers` SET `computer_name`='L22WS00' WHERE computer_name = 'L202WS00';
UPDATE `computers` SET `computer_name`='L22WS01' WHERE computer_name = 'L202WS01';
UPDATE `computers` SET `computer_name`='L22WS02' WHERE computer_name = 'L202WS02';
UPDATE `computers` SET `computer_name`='L22WS03' WHERE computer_name = 'L202WS03';
UPDATE `computers` SET `computer_name`='L22WS04' WHERE computer_name = 'L202WS04';
UPDATE `computers` SET `computer_name`='L22WS05' WHERE computer_name = 'L202WS05';
UPDATE `computers` SET `computer_name`='L22WS06' WHERE computer_name = 'L202WS06';
UPDATE `computers` SET `computer_name`='L22WS07' WHERE computer_name = 'L202WS07';
UPDATE `computers` SET `computer_name`='L22WS08' WHERE computer_name = 'L202WS08';
UPDATE `computers` SET `computer_name`='L22WS09' WHERE computer_name = 'L202WS09';
UPDATE `computers` SET `computer_name`='L22WS10' WHERE computer_name = 'L202WS10';
UPDATE `computers` SET `computer_name`='L22WS11' WHERE computer_name = 'L202WS11';
UPDATE `computers` SET `computer_name`='L22WS12' WHERE computer_name = 'L202WS12';
UPDATE `computers` SET `computer_name`='L22WS13' WHERE computer_name = 'L202WS13';
UPDATE `computers` SET `computer_name`='L22WS14' WHERE computer_name = 'L202WS14';
UPDATE `computers` SET `computer_name`='L22WS15' WHERE computer_name = 'L202WS15';
UPDATE `computers` SET `computer_name`='L22WS16' WHERE computer_name = 'L202WS16';
UPDATE `computers` SET `computer_name`='L22WS17' WHERE computer_name = 'L202WS17';
UPDATE `computers` SET `computer_name`='L22WS18' WHERE computer_name = 'L202WS18';
UPDATE `computers` SET `computer_name`='L22WS19' WHERE computer_name = 'L202WS19';
UPDATE `computers` SET `computer_name`='L22WS20' WHERE computer_name = 'L202WS20';
UPDATE `computers` SET `computer_name`='L22WS21' WHERE computer_name = 'L202WS21';
UPDATE `computers` SET `computer_name`='L22WS22' WHERE computer_name = 'L202WS22';
UPDATE `computers` SET `computer_name`='L22WS23' WHERE computer_name = 'L202WS23';
UPDATE `computers` SET `computer_name`='L22WS24' WHERE computer_name = 'L202WS24';
UPDATE `computers` SET `computer_name`='L22WS25' WHERE computer_name = 'L202WS25';
UPDATE `computers` SET `computer_name`='L22WS26' WHERE computer_name = 'L202WS26';
UPDATE `computers` SET `computer_name`='L22WS27' WHERE computer_name = 'L202WS27';
UPDATE `computers` SET `computer_name`='L22WS28' WHERE computer_name = 'L202WS28';
UPDATE `computers` SET `computer_name`='L22WS29' WHERE computer_name = 'L202WS29';
UPDATE `computers` SET `computer_name`='L22WS30' WHERE computer_name = 'L202WS30';
UPDATE `computers` SET `computer_name`='L22WS31' WHERE computer_name = 'L202WS31';
UPDATE `computers` SET `computer_name`='L22WS32' WHERE computer_name = 'L202WS32';
UPDATE `computers` SET `computer_name`='L22WS33' WHERE computer_name = 'L202WS33';
UPDATE `computers` SET `computer_name`='L22WS34' WHERE computer_name = 'L202WS34';
UPDATE `computers` SET `computer_name`='L22WS35' WHERE computer_name = 'L202WS35';
UPDATE `computers` SET `computer_name`='L22WS36' WHERE computer_name = 'L202WS36';
UPDATE `computers` SET `computer_name`='L22WS37' WHERE computer_name = 'L202WS37';
UPDATE `computers` SET `computer_name`='L22WS38' WHERE computer_name = 'L202WS38';
UPDATE `computers` SET `computer_name`='L22WS39' WHERE computer_name = 'L202WS39';
UPDATE `computers` SET `computer_name`='L22WS40' WHERE computer_name = 'L202WS40';
UPDATE `computers` SET `computer_name`='L22WS41' WHERE computer_name = 'L202WS41';
UPDATE `computers` SET `computer_name`='L22WS42' WHERE computer_name = 'L202WS42';
UPDATE `computers` SET `computer_name`='L22WS43' WHERE computer_name = 'L202WS43';
UPDATE `computers` SET `computer_name`='L22WS44' WHERE computer_name = 'L202WS44';
UPDATE `computers` SET `computer_name`='L22WS45' WHERE computer_name = 'L202WS45';
UPDATE `computers` SET `computer_name`='L22WS46' WHERE computer_name = 'L202WS46';
UPDATE `computers` SET `computer_name`='L22WS47' WHERE computer_name = 'L202WS47';
UPDATE `computers` SET `computer_name`='L22WS48' WHERE computer_name = 'L202WS48';

UPDATE `computers` SET `computer_name`='L23WS00' WHERE computer_name = 'L203WS00';
UPDATE `computers` SET `computer_name`='L23WS01' WHERE computer_name = 'L203WS01';
UPDATE `computers` SET `computer_name`='L23WS02' WHERE computer_name = 'L203WS02';
UPDATE `computers` SET `computer_name`='L23WS03' WHERE computer_name = 'L203WS03';
UPDATE `computers` SET `computer_name`='L23WS04' WHERE computer_name = 'L203WS04';
UPDATE `computers` SET `computer_name`='L23WS05' WHERE computer_name = 'L203WS05';
UPDATE `computers` SET `computer_name`='L23WS06' WHERE computer_name = 'L203WS06';
UPDATE `computers` SET `computer_name`='L23WS07' WHERE computer_name = 'L203WS07';
UPDATE `computers` SET `computer_name`='L23WS08' WHERE computer_name = 'L203WS08';
UPDATE `computers` SET `computer_name`='L23WS09' WHERE computer_name = 'L203WS09';
UPDATE `computers` SET `computer_name`='L23WS10' WHERE computer_name = 'L203WS10';
UPDATE `computers` SET `computer_name`='L23WS11' WHERE computer_name = 'L203WS11';
UPDATE `computers` SET `computer_name`='L23WS12' WHERE computer_name = 'L203WS12';
UPDATE `computers` SET `computer_name`='L23WS13' WHERE computer_name = 'L203WS13';
UPDATE `computers` SET `computer_name`='L23WS14' WHERE computer_name = 'L203WS14';
UPDATE `computers` SET `computer_name`='L23WS15' WHERE computer_name = 'L203WS15';
UPDATE `computers` SET `computer_name`='L23WS16' WHERE computer_name = 'L203WS16';
UPDATE `computers` SET `computer_name`='L23WS17' WHERE computer_name = 'L203WS17';
UPDATE `computers` SET `computer_name`='L23WS18' WHERE computer_name = 'L203WS18';
UPDATE `computers` SET `computer_name`='L23WS19' WHERE computer_name = 'L203WS19';
UPDATE `computers` SET `computer_name`='L23WS20' WHERE computer_name = 'L203WS20';
UPDATE `computers` SET `computer_name`='L23WS21' WHERE computer_name = 'L203WS21';
UPDATE `computers` SET `computer_name`='L23WS22' WHERE computer_name = 'L203WS22';
UPDATE `computers` SET `computer_name`='L23WS23' WHERE computer_name = 'L203WS23';
UPDATE `computers` SET `computer_name`='L23WS24' WHERE computer_name = 'L203WS24';
UPDATE `computers` SET `computer_name`='L23WS25' WHERE computer_name = 'L203WS25';
UPDATE `computers` SET `computer_name`='L23WS26' WHERE computer_name = 'L203WS26';
UPDATE `computers` SET `computer_name`='L23WS27' WHERE computer_name = 'L203WS27';
UPDATE `computers` SET `computer_name`='L23WS28' WHERE computer_name = 'L203WS28';
UPDATE `computers` SET `computer_name`='L23WS29' WHERE computer_name = 'L203WS29';
UPDATE `computers` SET `computer_name`='L23WS30' WHERE computer_name = 'L203WS30';
UPDATE `computers` SET `computer_name`='L23WS31' WHERE computer_name = 'L203WS31';
UPDATE `computers` SET `computer_name`='L23WS32' WHERE computer_name = 'L203WS32';
UPDATE `computers` SET `computer_name`='L23WS33' WHERE computer_name = 'L203WS33';
UPDATE `computers` SET `computer_name`='L23WS34' WHERE computer_name = 'L203WS34';
UPDATE `computers` SET `computer_name`='L23WS35' WHERE computer_name = 'L203WS35';
UPDATE `computers` SET `computer_name`='L23WS36' WHERE computer_name = 'L203WS36';
UPDATE `computers` SET `computer_name`='L23WS37' WHERE computer_name = 'L203WS37';
UPDATE `computers` SET `computer_name`='L23WS38' WHERE computer_name = 'L203WS38';
UPDATE `computers` SET `computer_name`='L23WS39' WHERE computer_name = 'L203WS39';
UPDATE `computers` SET `computer_name`='L23WS40' WHERE computer_name = 'L203WS40';
UPDATE `computers` SET `computer_name`='L23WS41' WHERE computer_name = 'L203WS41';
UPDATE `computers` SET `computer_name`='L23WS42' WHERE computer_name = 'L203WS42';
UPDATE `computers` SET `computer_name`='L23WS43' WHERE computer_name = 'L203WS43';
UPDATE `computers` SET `computer_name`='L23WS44' WHERE computer_name = 'L203WS44';
UPDATE `computers` SET `computer_name`='L23WS45' WHERE computer_name = 'L203WS45';
UPDATE `computers` SET `computer_name`='L23WS46' WHERE computer_name = 'L203WS46';
UPDATE `computers` SET `computer_name`='L23WS47' WHERE computer_name = 'L203WS47';
UPDATE `computers` SET `computer_name`='L23WS48' WHERE computer_name = 'L203WS48';

UPDATE `computers` SET `computer_name`='L24WS00' WHERE computer_name = 'L204WS00';
UPDATE `computers` SET `computer_name`='L24WS01' WHERE computer_name = 'L204WS01';
UPDATE `computers` SET `computer_name`='L24WS02' WHERE computer_name = 'L204WS02';
UPDATE `computers` SET `computer_name`='L24WS03' WHERE computer_name = 'L204WS03';
UPDATE `computers` SET `computer_name`='L24WS04' WHERE computer_name = 'L204WS04';
UPDATE `computers` SET `computer_name`='L24WS05' WHERE computer_name = 'L204WS05';
UPDATE `computers` SET `computer_name`='L24WS06' WHERE computer_name = 'L204WS06';
UPDATE `computers` SET `computer_name`='L24WS07' WHERE computer_name = 'L204WS07';
UPDATE `computers` SET `computer_name`='L24WS08' WHERE computer_name = 'L204WS08';
UPDATE `computers` SET `computer_name`='L24WS09' WHERE computer_name = 'L204WS09';
UPDATE `computers` SET `computer_name`='L24WS10' WHERE computer_name = 'L204WS10';
UPDATE `computers` SET `computer_name`='L24WS11' WHERE computer_name = 'L204WS11';
UPDATE `computers` SET `computer_name`='L24WS12' WHERE computer_name = 'L204WS12';
UPDATE `computers` SET `computer_name`='L24WS13' WHERE computer_name = 'L204WS13';
UPDATE `computers` SET `computer_name`='L24WS14' WHERE computer_name = 'L204WS14';
UPDATE `computers` SET `computer_name`='L24WS15' WHERE computer_name = 'L204WS15';
UPDATE `computers` SET `computer_name`='L24WS16' WHERE computer_name = 'L204WS16';
UPDATE `computers` SET `computer_name`='L24WS17' WHERE computer_name = 'L204WS17';
UPDATE `computers` SET `computer_name`='L24WS18' WHERE computer_name = 'L204WS18';
UPDATE `computers` SET `computer_name`='L24WS19' WHERE computer_name = 'L204WS19';
UPDATE `computers` SET `computer_name`='L24WS20' WHERE computer_name = 'L204WS20';
UPDATE `computers` SET `computer_name`='L24WS21' WHERE computer_name = 'L204WS21';
UPDATE `computers` SET `computer_name`='L24WS22' WHERE computer_name = 'L204WS22';
UPDATE `computers` SET `computer_name`='L24WS23' WHERE computer_name = 'L204WS23';
UPDATE `computers` SET `computer_name`='L24WS24' WHERE computer_name = 'L204WS24';
UPDATE `computers` SET `computer_name`='L24WS25' WHERE computer_name = 'L204WS25';
UPDATE `computers` SET `computer_name`='L24WS26' WHERE computer_name = 'L204WS26';
UPDATE `computers` SET `computer_name`='L24WS27' WHERE computer_name = 'L204WS27';
UPDATE `computers` SET `computer_name`='L24WS28' WHERE computer_name = 'L204WS28';
UPDATE `computers` SET `computer_name`='L24WS29' WHERE computer_name = 'L204WS29';
UPDATE `computers` SET `computer_name`='L24WS30' WHERE computer_name = 'L204WS30';
UPDATE `computers` SET `computer_name`='L24WS31' WHERE computer_name = 'L204WS31';
UPDATE `computers` SET `computer_name`='L24WS32' WHERE computer_name = 'L204WS32';
UPDATE `computers` SET `computer_name`='L24WS33' WHERE computer_name = 'L204WS33';
UPDATE `computers` SET `computer_name`='L24WS34' WHERE computer_name = 'L204WS34';
UPDATE `computers` SET `computer_name`='L24WS35' WHERE computer_name = 'L204WS35';
UPDATE `computers` SET `computer_name`='L24WS36' WHERE computer_name = 'L204WS36';
UPDATE `computers` SET `computer_name`='L24WS37' WHERE computer_name = 'L204WS37';
UPDATE `computers` SET `computer_name`='L24WS38' WHERE computer_name = 'L204WS38';
UPDATE `computers` SET `computer_name`='L24WS39' WHERE computer_name = 'L204WS39';
UPDATE `computers` SET `computer_name`='L24WS40' WHERE computer_name = 'L204WS40';
UPDATE `computers` SET `computer_name`='L24WS41' WHERE computer_name = 'L204WS41';
UPDATE `computers` SET `computer_name`='L24WS42' WHERE computer_name = 'L204WS42';
UPDATE `computers` SET `computer_name`='L24WS43' WHERE computer_name = 'L204WS43';
UPDATE `computers` SET `computer_name`='L24WS44' WHERE computer_name = 'L204WS44';
UPDATE `computers` SET `computer_name`='L24WS45' WHERE computer_name = 'L204WS45';
UPDATE `computers` SET `computer_name`='L24WS46' WHERE computer_name = 'L204WS46';
UPDATE `computers` SET `computer_name`='L24WS47' WHERE computer_name = 'L204WS47';
UPDATE `computers` SET `computer_name`='L24WS48' WHERE computer_name = 'L204WS48';

UPDATE `computers` SET `computer_name`='L25WS00' WHERE computer_name = 'L205WS00';
UPDATE `computers` SET `computer_name`='L25WS01' WHERE computer_name = 'L205WS01';
UPDATE `computers` SET `computer_name`='L25WS02' WHERE computer_name = 'L205WS02';
UPDATE `computers` SET `computer_name`='L25WS03' WHERE computer_name = 'L205WS03';
UPDATE `computers` SET `computer_name`='L25WS04' WHERE computer_name = 'L205WS04';
UPDATE `computers` SET `computer_name`='L25WS05' WHERE computer_name = 'L205WS05';
UPDATE `computers` SET `computer_name`='L25WS06' WHERE computer_name = 'L205WS06';
UPDATE `computers` SET `computer_name`='L25WS07' WHERE computer_name = 'L205WS07';
UPDATE `computers` SET `computer_name`='L25WS08' WHERE computer_name = 'L205WS08';
UPDATE `computers` SET `computer_name`='L25WS09' WHERE computer_name = 'L205WS09';
UPDATE `computers` SET `computer_name`='L25WS10' WHERE computer_name = 'L205WS10';
UPDATE `computers` SET `computer_name`='L25WS11' WHERE computer_name = 'L205WS11';
UPDATE `computers` SET `computer_name`='L25WS12' WHERE computer_name = 'L205WS12';
UPDATE `computers` SET `computer_name`='L25WS13' WHERE computer_name = 'L205WS13';
UPDATE `computers` SET `computer_name`='L25WS14' WHERE computer_name = 'L205WS14';
UPDATE `computers` SET `computer_name`='L25WS15' WHERE computer_name = 'L205WS15';
UPDATE `computers` SET `computer_name`='L25WS16' WHERE computer_name = 'L205WS16';
UPDATE `computers` SET `computer_name`='L25WS17' WHERE computer_name = 'L205WS17';
UPDATE `computers` SET `computer_name`='L25WS18' WHERE computer_name = 'L205WS18';
UPDATE `computers` SET `computer_name`='L25WS19' WHERE computer_name = 'L205WS19';
UPDATE `computers` SET `computer_name`='L25WS20' WHERE computer_name = 'L205WS20';
UPDATE `computers` SET `computer_name`='L25WS21' WHERE computer_name = 'L205WS21';
UPDATE `computers` SET `computer_name`='L25WS22' WHERE computer_name = 'L205WS22';
UPDATE `computers` SET `computer_name`='L25WS23' WHERE computer_name = 'L205WS23';
UPDATE `computers` SET `computer_name`='L25WS24' WHERE computer_name = 'L205WS24';
UPDATE `computers` SET `computer_name`='L25WS25' WHERE computer_name = 'L205WS25';
UPDATE `computers` SET `computer_name`='L25WS26' WHERE computer_name = 'L205WS26';
UPDATE `computers` SET `computer_name`='L25WS27' WHERE computer_name = 'L205WS27';
UPDATE `computers` SET `computer_name`='L25WS28' WHERE computer_name = 'L205WS28';
UPDATE `computers` SET `computer_name`='L25WS29' WHERE computer_name = 'L205WS29';
UPDATE `computers` SET `computer_name`='L25WS30' WHERE computer_name = 'L205WS30';
UPDATE `computers` SET `computer_name`='L25WS31' WHERE computer_name = 'L205WS31';
UPDATE `computers` SET `computer_name`='L25WS32' WHERE computer_name = 'L205WS32';
UPDATE `computers` SET `computer_name`='L25WS33' WHERE computer_name = 'L205WS33';
UPDATE `computers` SET `computer_name`='L25WS34' WHERE computer_name = 'L205WS34';
UPDATE `computers` SET `computer_name`='L25WS35' WHERE computer_name = 'L205WS35';
UPDATE `computers` SET `computer_name`='L25WS36' WHERE computer_name = 'L205WS36';
UPDATE `computers` SET `computer_name`='L25WS37' WHERE computer_name = 'L205WS37';
UPDATE `computers` SET `computer_name`='L25WS38' WHERE computer_name = 'L205WS38';
UPDATE `computers` SET `computer_name`='L25WS39' WHERE computer_name = 'L205WS39';
UPDATE `computers` SET `computer_name`='L25WS40' WHERE computer_name = 'L205WS40';
UPDATE `computers` SET `computer_name`='L25WS41' WHERE computer_name = 'L205WS41';
UPDATE `computers` SET `computer_name`='L25WS42' WHERE computer_name = 'L205WS42';
UPDATE `computers` SET `computer_name`='L25WS43' WHERE computer_name = 'L205WS43';
UPDATE `computers` SET `computer_name`='L25WS44' WHERE computer_name = 'L205WS44';
UPDATE `computers` SET `computer_name`='L25WS45' WHERE computer_name = 'L205WS45';
UPDATE `computers` SET `computer_name`='L25WS46' WHERE computer_name = 'L205WS46';
UPDATE `computers` SET `computer_name`='L25WS47' WHERE computer_name = 'L205WS47';
UPDATE `computers` SET `computer_name`='L25WS48' WHERE computer_name = 'L205WS48';
