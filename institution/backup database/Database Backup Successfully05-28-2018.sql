DROP TABLE IF EXISTS aboutus;

CREATE TABLE `aboutus` (
  `abid` int(11) NOT NULL AUTO_INCREMENT,
  `who_we_are` text NOT NULL,
  `mission` text NOT NULL,
  PRIMARY KEY (`abid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS additional_fees;

CREATE TABLE `additional_fees` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `fee` varchar(200) NOT NULL,
  `Amount` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO additional_fees VALUES("4","4","0107742310","Late Payment","50");


DROP TABLE IF EXISTS attachment;

CREATE TABLE `attachment` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `attached_file` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO attachment VALUES("5","4","2","document/7752_File_IMG-20180419-WA0001.jpg","2018-05-01 23:10:42");
INSERT INTO attachment VALUES("6","4","2","document/6401_File_bulksms.pdf","2018-05-01 23:10:33");


DROP TABLE IF EXISTS backup;

CREATE TABLE `backup` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `tracking_id` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS banner;

CREATE TABLE `banner` (
  `banaid` int(11) NOT NULL AUTO_INCREMENT,
  `bannar` text NOT NULL,
  `title` varchar(150) NOT NULL,
  `short_desc` varchar(600) NOT NULL,
  PRIMARY KEY (`banaid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO banner VALUES("1","../banner/home1-3.jpg","SIGN THE PETITION NOW","Tell the World you prefer to stand with IMONFUND");
INSERT INTO banner VALUES("2","../banner/heading-1.jpg","JOIN OUT MOVEMENT","We are Good in Building Better Communities Across the World");
INSERT INTO banner VALUES("3","../banner/home.jpg","JOIN OUR MOVEMENT","Contribute and Support our Work Today to Improve this Nation");


DROP TABLE IF EXISTS battachment;

CREATE TABLE `battachment` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `attached_file` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS bcountries;

CREATE TABLE `bcountries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `alpha_2` varchar(200) NOT NULL DEFAULT '',
  `alpha_3` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=250 DEFAULT CHARSET=utf8;

INSERT INTO bcountries VALUES("1","Afghanistan","fl","afg");
INSERT INTO bcountries VALUES("2","Aland Islands","ax","ala");
INSERT INTO bcountries VALUES("3","Albania","al","alb");
INSERT INTO bcountries VALUES("4","Algeria","dz","dza");
INSERT INTO bcountries VALUES("5","American Samoa","as","asm");
INSERT INTO bcountries VALUES("6","Andorra","ad","and");
INSERT INTO bcountries VALUES("7","Angola","ao","ago");
INSERT INTO bcountries VALUES("8","Anguilla","ai","aia");
INSERT INTO bcountries VALUES("9","Antarctica","aq","ata");
INSERT INTO bcountries VALUES("10","Antigua and Barbuda","ag","atg");
INSERT INTO bcountries VALUES("11","Argentina","ar","arg");
INSERT INTO bcountries VALUES("12","Armenia","am","arm");
INSERT INTO bcountries VALUES("13","Aruba","aw","abw");
INSERT INTO bcountries VALUES("14","Australia","au","aus");
INSERT INTO bcountries VALUES("15","Austria","at","aut");
INSERT INTO bcountries VALUES("16","Azerbaijan","az","aze");
INSERT INTO bcountries VALUES("17","Bahamas","bs","bhs");
INSERT INTO bcountries VALUES("18","Bahrain","bh","bhr");
INSERT INTO bcountries VALUES("19","Bangladesh","bd","bgd");
INSERT INTO bcountries VALUES("20","Barbados","bb","brb");
INSERT INTO bcountries VALUES("21","Belarus","by","blr");
INSERT INTO bcountries VALUES("22","Belgium","be","bel");
INSERT INTO bcountries VALUES("23","Belize","bz","blz");
INSERT INTO bcountries VALUES("24","Benin","bj","ben");
INSERT INTO bcountries VALUES("25","Bermuda","bm","bmu");
INSERT INTO bcountries VALUES("26","Bhutan","bt","btn");
INSERT INTO bcountries VALUES("27","Bolivia, Plurinational State of","bo","bol");
INSERT INTO bcountries VALUES("28","Bonaire, Sint Eustatius and Saba","bq","bes");
INSERT INTO bcountries VALUES("29","Bosnia and Herzegovina","ba","bih");
INSERT INTO bcountries VALUES("30","Botswana","bw","bwa");
INSERT INTO bcountries VALUES("31","Bouvet Island","bv","bvt");
INSERT INTO bcountries VALUES("32","Brazil","br","bra");
INSERT INTO bcountries VALUES("33","British Indian Ocean Territory","io","iot");
INSERT INTO bcountries VALUES("34","Brunei Darussalam","bn","brn");
INSERT INTO bcountries VALUES("35","Bulgaria","bg","bgr");
INSERT INTO bcountries VALUES("36","Burkina Faso","bf","bfa");
INSERT INTO bcountries VALUES("37","Burundi","bi","bdi");
INSERT INTO bcountries VALUES("38","Cambodia","kh","khm");
INSERT INTO bcountries VALUES("39","Cameroon","cm","cmr");
INSERT INTO bcountries VALUES("40","Canada","ca","can");
INSERT INTO bcountries VALUES("41","Cape Verde","cv","cpv");
INSERT INTO bcountries VALUES("42","Cayman Islands","ky","cym");
INSERT INTO bcountries VALUES("43","Central African Republic","cf","caf");
INSERT INTO bcountries VALUES("44","Chad","td","tcd");
INSERT INTO bcountries VALUES("45","Chile","cl","chl");
INSERT INTO bcountries VALUES("46","China","cn","chn");
INSERT INTO bcountries VALUES("47","Christmas Island","cx","cxr");
INSERT INTO bcountries VALUES("48","Cocos (Keeling) Islands","cc","cck");
INSERT INTO bcountries VALUES("49","Colombia","co","col");
INSERT INTO bcountries VALUES("50","Comoros","km","com");
INSERT INTO bcountries VALUES("51","Congo","cg","cog");
INSERT INTO bcountries VALUES("52","Congo, The Democratic Republic of the","cd","cod");
INSERT INTO bcountries VALUES("53","Cook Islands","ck","cok");
INSERT INTO bcountries VALUES("54","Costa Rica","cr","cri");
INSERT INTO bcountries VALUES("55","Cote d\'Ivoire","ci","civ");
INSERT INTO bcountries VALUES("56","Croatia","hr","hrv");
INSERT INTO bcountries VALUES("57","Cuba","cu","cub");
INSERT INTO bcountries VALUES("58","Curacao","cw","cuw");
INSERT INTO bcountries VALUES("59","Cyprus","cy","cyp");
INSERT INTO bcountries VALUES("60","Czech Republic","cz","cze");
INSERT INTO bcountries VALUES("61","Denmark","dk","dnk");
INSERT INTO bcountries VALUES("62","Djibouti","dj","dji");
INSERT INTO bcountries VALUES("63","Dominica","dm","dma");
INSERT INTO bcountries VALUES("64","Dominican Republic","do","dom");
INSERT INTO bcountries VALUES("65","Ecuador","ec","ecu");
INSERT INTO bcountries VALUES("66","Egypt","eg","egy");
INSERT INTO bcountries VALUES("67","El Salvador","sv","slv");
INSERT INTO bcountries VALUES("68","Equatorial Guinea","gq","gnq");
INSERT INTO bcountries VALUES("69","Eritrea","er","eri");
INSERT INTO bcountries VALUES("70","Estonia","ee","est");
INSERT INTO bcountries VALUES("71","Ethiopia","et","eth");
INSERT INTO bcountries VALUES("72","Falkland Islands (Malvinas)","fk","flk");
INSERT INTO bcountries VALUES("73","Faroe Islands","fo","fro");
INSERT INTO bcountries VALUES("74","Fiji","fj","fji");
INSERT INTO bcountries VALUES("75","Finland","fi","fin");
INSERT INTO bcountries VALUES("76","France","fr","fra");
INSERT INTO bcountries VALUES("77","French Guiana","gf","guf");
INSERT INTO bcountries VALUES("78","French Polynesia","pf","pyf");
INSERT INTO bcountries VALUES("79","French Southern Territories","tf","atf");
INSERT INTO bcountries VALUES("80","Gabon","ga","gab");
INSERT INTO bcountries VALUES("81","Gambia","gm","gmb");
INSERT INTO bcountries VALUES("82","Georgia","ge","geo");
INSERT INTO bcountries VALUES("83","Germany","de","deu");
INSERT INTO bcountries VALUES("84","Ghana","gh","gha");
INSERT INTO bcountries VALUES("85","Gibraltar","gi","gib");
INSERT INTO bcountries VALUES("86","Greece","gr","grc");
INSERT INTO bcountries VALUES("87","Greenland","gl","grl");
INSERT INTO bcountries VALUES("88","Grenada","gd","grd");
INSERT INTO bcountries VALUES("89","Guadeloupe","gp","glp");
INSERT INTO bcountries VALUES("90","Guam","gu","gum");
INSERT INTO bcountries VALUES("91","Guatemala","gt","gtm");
INSERT INTO bcountries VALUES("92","Guernsey","gg","ggy");
INSERT INTO bcountries VALUES("93","Guinea","gn","gin");
INSERT INTO bcountries VALUES("94","Guinea-Bissau","gw","gnb");
INSERT INTO bcountries VALUES("95","Guyana","gy","guy");
INSERT INTO bcountries VALUES("96","Haiti","ht","hti");
INSERT INTO bcountries VALUES("97","Heard Island and McDonald Islands","hm","hmd");
INSERT INTO bcountries VALUES("98","Holy See (Vatican City State)","va","vat");
INSERT INTO bcountries VALUES("99","Honduras","hn","hnd");
INSERT INTO bcountries VALUES("100","Hong Kong","hk","hkg");
INSERT INTO bcountries VALUES("101","Hungary","hu","hun");
INSERT INTO bcountries VALUES("102","Iceland","is","isl");
INSERT INTO bcountries VALUES("103","India","in","ind");
INSERT INTO bcountries VALUES("104","Indonesia","id","idn");
INSERT INTO bcountries VALUES("105","Iran, Islamic Republic of","ir","irn");
INSERT INTO bcountries VALUES("106","Iraq","iq","irq");
INSERT INTO bcountries VALUES("107","Ireland","ie","irl");
INSERT INTO bcountries VALUES("108","Isle of Man","im","imn");
INSERT INTO bcountries VALUES("109","Israel","il","isr");
INSERT INTO bcountries VALUES("110","Italy","it","ita");
INSERT INTO bcountries VALUES("111","Jamaica","jm","jam");
INSERT INTO bcountries VALUES("112","Japan","jp","jpn");
INSERT INTO bcountries VALUES("113","Jersey","je","jey");
INSERT INTO bcountries VALUES("114","Jordan","jo","jor");
INSERT INTO bcountries VALUES("115","Kazakhstan","kz","kaz");
INSERT INTO bcountries VALUES("116","Kenya","ke","ken");
INSERT INTO bcountries VALUES("117","Kiribati","ki","kir");
INSERT INTO bcountries VALUES("118","Korea, Democratic People\'s Republic of","kp","prk");
INSERT INTO bcountries VALUES("119","Korea, Republic of","kr","kor");
INSERT INTO bcountries VALUES("120","Kuwait","kw","kwt");
INSERT INTO bcountries VALUES("121","Kyrgyzstan","kg","kgz");
INSERT INTO bcountries VALUES("122","Lao People\'s Democratic Republic","la","lao");
INSERT INTO bcountries VALUES("123","Latvia","lv","lva");
INSERT INTO bcountries VALUES("124","Lebanon","lb","lbn");
INSERT INTO bcountries VALUES("125","Lesotho","ls","lso");
INSERT INTO bcountries VALUES("126","Liberia","lr","lbr");
INSERT INTO bcountries VALUES("127","Libyan Arab Jamahiriya","ly","lby");
INSERT INTO bcountries VALUES("128","Liechtenstein","li","lie");
INSERT INTO bcountries VALUES("129","Lithuania","lt","ltu");
INSERT INTO bcountries VALUES("130","Luxembourg","lu","lux");
INSERT INTO bcountries VALUES("131","Macao","mo","mac");
INSERT INTO bcountries VALUES("132","Macedonia, The former Yugoslav Republic of","mk","mkd");
INSERT INTO bcountries VALUES("133","Madagascar","mg","mdg");
INSERT INTO bcountries VALUES("134","Malawi","mw","mwi");
INSERT INTO bcountries VALUES("135","Malaysia","my","mys");
INSERT INTO bcountries VALUES("136","Maldives","mv","mdv");
INSERT INTO bcountries VALUES("137","Mali","ml","mli");
INSERT INTO bcountries VALUES("138","Malta","mt","mlt");
INSERT INTO bcountries VALUES("139","Marshall Islands","mh","mhl");
INSERT INTO bcountries VALUES("140","Martinique","mq","mtq");
INSERT INTO bcountries VALUES("141","Mauritania","mr","mrt");
INSERT INTO bcountries VALUES("142","Mauritius","mu","mus");
INSERT INTO bcountries VALUES("143","Mayotte","yt","myt");
INSERT INTO bcountries VALUES("144","Mexico","mx","mex");
INSERT INTO bcountries VALUES("145","Micronesia, Federated States of","fm","fsm");
INSERT INTO bcountries VALUES("146","Moldova, Republic of","md","mda");
INSERT INTO bcountries VALUES("147","Monaco","mc","mco");
INSERT INTO bcountries VALUES("148","Mongolia","mn","mng");
INSERT INTO bcountries VALUES("149","Montenegro","me","mne");
INSERT INTO bcountries VALUES("150","Montserrat","ms","msr");
INSERT INTO bcountries VALUES("151","Morocco","ma","mar");
INSERT INTO bcountries VALUES("152","Mozambique","mz","moz");
INSERT INTO bcountries VALUES("153","Myanmar","mm","mmr");
INSERT INTO bcountries VALUES("154","Namibia","na","nam");
INSERT INTO bcountries VALUES("155","Nauru","nr","nru");
INSERT INTO bcountries VALUES("156","Nepal","np","npl");
INSERT INTO bcountries VALUES("157","Netherlands","nl","nld");
INSERT INTO bcountries VALUES("158","New Caledonia","nc","ncl");
INSERT INTO bcountries VALUES("159","New Zealand","nz","nzl");
INSERT INTO bcountries VALUES("160","Nicaragua","ni","nic");
INSERT INTO bcountries VALUES("161","Niger","ne","ner");
INSERT INTO bcountries VALUES("162","Nigeria","ng","nga");
INSERT INTO bcountries VALUES("163","Niue","nu","niu");
INSERT INTO bcountries VALUES("164","Norfolk Island","nf","nfk");
INSERT INTO bcountries VALUES("165","Northern Mariana Islands","mp","mnp");
INSERT INTO bcountries VALUES("166","Norway","no","nor");
INSERT INTO bcountries VALUES("167","Oman","om","omn");
INSERT INTO bcountries VALUES("168","Pakistan","pk","pak");
INSERT INTO bcountries VALUES("169","Palau","pw","plw");
INSERT INTO bcountries VALUES("170","Palestinian Territory, Occupied","ps","pse");
INSERT INTO bcountries VALUES("171","Panama","pa","pan");
INSERT INTO bcountries VALUES("172","Papua New Guinea","pg","png");
INSERT INTO bcountries VALUES("173","Paraguay","py","pry");
INSERT INTO bcountries VALUES("174","Peru","pe","per");
INSERT INTO bcountries VALUES("175","Philippines","ph","phl");
INSERT INTO bcountries VALUES("176","Pitcairn","pn","pcn");
INSERT INTO bcountries VALUES("177","Poland","pl","pol");
INSERT INTO bcountries VALUES("178","Portugal","pt","prt");
INSERT INTO bcountries VALUES("179","Puerto Rico","pr","pri");
INSERT INTO bcountries VALUES("180","Qatar","qa","qat");
INSERT INTO bcountries VALUES("181","Reunion","re","reu");
INSERT INTO bcountries VALUES("182","Romania","ro","rou");
INSERT INTO bcountries VALUES("183","Russian Federation","ru","rus");
INSERT INTO bcountries VALUES("184","Rwanda","rw","rwa");
INSERT INTO bcountries VALUES("185","Saint Barthelemy","bl","blm");
INSERT INTO bcountries VALUES("186","Saint Helena, Ascension and Tristan Da Cunha","sh","shn");
INSERT INTO bcountries VALUES("187","Saint Kitts and Nevis","kn","kna");
INSERT INTO bcountries VALUES("188","Saint Lucia","lc","lca");
INSERT INTO bcountries VALUES("189","Saint Martin (French Part)","mf","maf");
INSERT INTO bcountries VALUES("190","Saint Pierre and Miquelon","pm","spm");
INSERT INTO bcountries VALUES("191","Saint Vincent and The Grenadines","vc","vct");
INSERT INTO bcountries VALUES("192","Samoa","ws","wsm");
INSERT INTO bcountries VALUES("193","San Marino","sm","smr");
INSERT INTO bcountries VALUES("194","Sao Tome and Principe","st","stp");
INSERT INTO bcountries VALUES("195","Saudi Arabia","sa","sau");
INSERT INTO bcountries VALUES("196","Senegal","sn","sen");
INSERT INTO bcountries VALUES("197","Serbia","rs","srb");
INSERT INTO bcountries VALUES("198","Seychelles","sc","syc");
INSERT INTO bcountries VALUES("199","Sierra Leone","sl","sle");
INSERT INTO bcountries VALUES("200","Singapore","sg","sgp");
INSERT INTO bcountries VALUES("201","Sint Maarten (Dutch Part)","sx","sxm");
INSERT INTO bcountries VALUES("202","Slovakia","sk","svk");
INSERT INTO bcountries VALUES("203","Slovenia","si","svn");
INSERT INTO bcountries VALUES("204","Solomon Islands","sb","slb");
INSERT INTO bcountries VALUES("205","Somalia","so","som");
INSERT INTO bcountries VALUES("206","South Africa","za","zaf");
INSERT INTO bcountries VALUES("207","South Georgia and The South Sandwich Islands","gs","sgs");
INSERT INTO bcountries VALUES("208","South Sudan","ss","ssd");
INSERT INTO bcountries VALUES("209","Spain","es","esp");
INSERT INTO bcountries VALUES("210","Sri Lanka","lk","lka");
INSERT INTO bcountries VALUES("211","Sudan","sd","sdn");
INSERT INTO bcountries VALUES("212","Suriname","sr","sur");
INSERT INTO bcountries VALUES("213","Svalbard and Jan Mayen","sj","sjm");
INSERT INTO bcountries VALUES("214","Swaziland","sz","swz");
INSERT INTO bcountries VALUES("215","Sweden","se","swe");
INSERT INTO bcountries VALUES("216","Switzerland","ch","che");
INSERT INTO bcountries VALUES("217","Syrian Arab Republic","sy","syr");
INSERT INTO bcountries VALUES("218","Taiwan, Province of China","tw","twn");
INSERT INTO bcountries VALUES("219","Tajikistan","tj","tjk");
INSERT INTO bcountries VALUES("220","Tanzania, United Republic of","tz","tza");
INSERT INTO bcountries VALUES("221","Thailand","th","tha");
INSERT INTO bcountries VALUES("222","Timor-Leste","tl","tls");
INSERT INTO bcountries VALUES("223","Togo","tg","tgo");
INSERT INTO bcountries VALUES("224","Tokelau","tk","tkl");
INSERT INTO bcountries VALUES("225","Tonga","to","ton");
INSERT INTO bcountries VALUES("226","Trinidad and Tobago","tt","tto");
INSERT INTO bcountries VALUES("227","Tunisia","tn","tun");
INSERT INTO bcountries VALUES("228","Turkey","tr","tur");
INSERT INTO bcountries VALUES("229","Turkmenistan","tm","tkm");
INSERT INTO bcountries VALUES("230","Turks and Caicos Islands","tc","tca");
INSERT INTO bcountries VALUES("231","Tuvalu","tv","tuv");
INSERT INTO bcountries VALUES("232","Uganda","ug","uga");
INSERT INTO bcountries VALUES("233","Ukraine","ua","ukr");
INSERT INTO bcountries VALUES("234","United Arab Emirates","ae","are");
INSERT INTO bcountries VALUES("235","United Kingdom","gb","gbr");
INSERT INTO bcountries VALUES("236","United States","us","usa");
INSERT INTO bcountries VALUES("237","United States Minor Outlying Islands","um","umi");
INSERT INTO bcountries VALUES("238","Uruguay","uy","ury");
INSERT INTO bcountries VALUES("239","Uzbekistan","uz","uzb");
INSERT INTO bcountries VALUES("240","Vanuatu","vu","vut");
INSERT INTO bcountries VALUES("241","Venezuela, Bolivarian Republic of","ve","ven");
INSERT INTO bcountries VALUES("242","Viet Nam","vn","vnm");
INSERT INTO bcountries VALUES("243","Virgin Islands, British","vg","vgb");
INSERT INTO bcountries VALUES("244","Virgin Islands, U.S.","vi","vir");
INSERT INTO bcountries VALUES("245","Wallis and Futuna","wf","wlf");
INSERT INTO bcountries VALUES("246","Western Sahara","eh","esh");
INSERT INTO bcountries VALUES("247","Yemen","ye","yem");
INSERT INTO bcountries VALUES("248","Zambia","zm","zmb");
INSERT INTO bcountries VALUES("249","Zimbabwe","zw","zwe");


DROP TABLE IF EXISTS borrowers;

CREATE TABLE `borrowers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(10) NOT NULL,
  `fname` varchar(200) NOT NULL,
  `lname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `dob` varchar(15) NOT NULL,
  `wstatus` varchar(50) NOT NULL,
  `unumber` varchar(50) NOT NULL,
  `bizname` varchar(200) NOT NULL,
  `addrs1` text NOT NULL,
  `addrs2` text NOT NULL,
  `city` varchar(200) NOT NULL,
  `state` varchar(200) NOT NULL,
  `zip` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `community_role` varchar(100) NOT NULL,
  `posts` varchar(200) NOT NULL,
  `account` varchar(200) NOT NULL,
  `balance` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_withdraw_date` date NOT NULL COMMENT 'YYYY-MM-DD',
  `status` varchar(200) NOT NULL,
  `lofficer` varchar(50) NOT NULL,
  `c_sign` varchar(500) NOT NULL,
  `branchid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO borrowers VALUES("1","","Olatunde","Timilehin","timilehingbemi@gmail.com","08101750845","","","","","","No. 17, Araromi Estate,","Ikeja Lagos.","Lagos","Lagos","23401","Nigeria","Author","0","0102460083","148905.55","img/imtimi.jpg","2018-05-28 02:24:44","2018-03-31","Completed","","img/sign1.png","");
INSERT INTO borrowers VALUES("2","","Akinade","Ayodeji","critech.getsolution@yahoo.com","09057102359","","","","","","No. 138, Akano Ifelagba Street","Off Olunde, Ibadan","Ibadan","Oyo","23402","Nigeria","Contributor","0","0107742310","45639.45","img/ay1.jpg","2018-05-28 01:56:27","2018-03-31","Completed","","img/sign.png","");


DROP TABLE IF EXISTS branches;

CREATE TABLE `branches` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `bname` varchar(200) NOT NULL,
  `bopendate` varchar(100) NOT NULL,
  `bcountry` char(100) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `branch_addrs` varchar(200) NOT NULL,
  `branch_city` varchar(200) NOT NULL,
  `branch_province` varchar(200) NOT NULL,
  `branch_zipcode` varchar(200) NOT NULL,
  `branch_landline` varchar(200) NOT NULL,
  `branch_mobile` varchar(50) NOT NULL,
  `minloan_amount` varchar(100) NOT NULL,
  `maxloan_amount` varchar(100) NOT NULL,
  `min_interest_rate` varchar(100) NOT NULL,
  `max_interest_rate` varchar(100) NOT NULL,
  `branchid` varchar(50) NOT NULL,
  `bstatus` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

INSERT INTO branches VALUES("16","Branch #2","2018-05-14","Nigeria","N","Ijebu Ode, Ogun State","Ijebu Ode","Nigeria â€” Ogun","23403","0089","0289837838","10","5000000","0","15","BR19157","Operational");


DROP TABLE IF EXISTS campaign_cat;

CREATE TABLE `campaign_cat` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `c_category` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO campaign_cat VALUES("6","Women Business Projects");
INSERT INTO campaign_cat VALUES("7","Educational & Student Innovation");
INSERT INTO campaign_cat VALUES("9","Community Empowerment ");


DROP TABLE IF EXISTS campaign_pay_history;

CREATE TABLE `campaign_pay_history` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `pid` varchar(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `pdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pstatus` varchar(100) NOT NULL COMMENT 'Completed or Fail or Pending',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS causes;

CREATE TABLE `causes` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `b_id` varchar(200) NOT NULL,
  `campaign_image` varchar(200) NOT NULL,
  `campaign_title` varchar(350) NOT NULL,
  `campaign_desc` text NOT NULL,
  `current_fund` varchar(200) NOT NULL,
  `budget` varchar(200) NOT NULL,
  `total_contributer` varchar(100) NOT NULL,
  `campaign_type` varchar(200) NOT NULL COMMENT 'Normal or Featured',
  `c_category` varchar(250) NOT NULL,
  `campaign_fee` varchar(200) NOT NULL COMMENT 'in %',
  `msg_to_donor` text NOT NULL,
  `twitter_handler` varchar(200) NOT NULL,
  `location` text NOT NULL,
  `dfrom` varchar(50) NOT NULL,
  `dto` varchar(50) NOT NULL,
  `campaign_status` varchar(200) NOT NULL COMMENT 'Pre-Approve or Disapprove or Approved',
  `tname` varchar(100) NOT NULL,
  `designation` varchar(150) NOT NULL,
  `aboutus` text NOT NULL,
  `branchid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO causes VALUES("1","1","img/cs1.jpg","CriTect Educational Scholarship Project","<p>CriTect Scholarship is going to be available for all student in tertiary instittion, colleges and the likes.</p>\n","0.00","3200.00","0","Donation","Educational & Student Innovation","3","Thanks for your contribution","@smsteams1","Lagos, Nigeria","2018-05-27","2018-07-27","Disapproved","Akinade Ayodeji","CEO","We are No. 1 IT Industry in Nigeria.","");


DROP TABLE IF EXISTS causes_note;

CREATE TABLE `causes_note` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `campaign_id` varchar(50) NOT NULL,
  `staffid` varchar(50) NOT NULL,
  `cstatus` varchar(20) NOT NULL,
  `cnote` text NOT NULL,
  `note_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO causes_note VALUES("3","1","Loan=21319580","Pending","Campaign waiting for approval from the concerned department, please retransmitted.","2018-05-27 11:06:01");
INSERT INTO causes_note VALUES("4","1","Loan=21319580","Disapproved","Sorry! The Campaign does not comply with our privacy policy, kindly re-adjust","2018-05-27 11:07:26");


DROP TABLE IF EXISTS collateral;

CREATE TABLE `collateral` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `idm` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type_of_collateral` varchar(200) NOT NULL,
  `model` varchar(200) NOT NULL,
  `make` varchar(200) NOT NULL,
  `serial_number` varchar(200) NOT NULL,
  `estimated_price` varchar(200) NOT NULL,
  `proof_of_ownership` text NOT NULL,
  `cimage` text NOT NULL,
  `observation` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO collateral VALUES("3","4","0107742310","CriTech Global Enterprises","Company","BN 2535016","CAC","NIL","20000","document/5508_File_copyrights_document_6a.pdf","document/Contract Agreement for USA Customers.pdf","Good condition");


DROP TABLE IF EXISTS deptors;

CREATE TABLE `deptors` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `lid` varchar(50) NOT NULL,
  `uaccount` varchar(20) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `lstatus` varchar(20) NOT NULL,
  `expire_date` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO deptors VALUES("2","LID-8875671","0107742310","5000","NotPaid","2019-05-09");
INSERT INTO deptors VALUES("3","LID-8875671","0107742310","5000","NotPaid","2019-05-09");


DROP TABLE IF EXISTS emp_permission;

CREATE TABLE `emp_permission` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tid` varchar(200) NOT NULL,
  `module_name` varchar(350) NOT NULL,
  `pcreate` varchar(20) NOT NULL,
  `pread` varchar(20) NOT NULL,
  `pupdate` varchar(20) NOT NULL,
  `pdelete` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;

INSERT INTO emp_permission VALUES("45","Loan=21319580","Branches","1","1","1","1");
INSERT INTO emp_permission VALUES("46","Loan=21319580","Borrower Details","1","1","1","1");
INSERT INTO emp_permission VALUES("47","Loan=21319580","Employee Wallet","1","1","1","1");
INSERT INTO emp_permission VALUES("48","Loan=21319580","Loan Details","1","1","1","1");
INSERT INTO emp_permission VALUES("49","Loan=21319580","Internal Message","1","1","0","0");
INSERT INTO emp_permission VALUES("50","Loan=21319580","Missed Payment","1","1","1","1");
INSERT INTO emp_permission VALUES("51","Loan=21319580","Payment","1","1","1","1");
INSERT INTO emp_permission VALUES("52","Loan=21319580","Employee Details","1","1","1","1");
INSERT INTO emp_permission VALUES("53","Loan=21319580","Module Permission","1","1","1","1");
INSERT INTO emp_permission VALUES("54","Loan=21319580","Savings Account","1","1","1","1");
INSERT INTO emp_permission VALUES("55","Loan=21319580","General Settings","1","1","1","0");
INSERT INTO emp_permission VALUES("56","Loan=21319580","Expenses","1","1","1","1");
INSERT INTO emp_permission VALUES("57","Loan=21319580","Payroll","1","1","1","1");
INSERT INTO emp_permission VALUES("58","Loan=21319580","Collateral Registered","1","1","1","1");
INSERT INTO emp_permission VALUES("59","Loan=21319580","Reports","1","1","1","1");
INSERT INTO emp_permission VALUES("60","Loan=172643433","Internal Message","1","1","0","0");
INSERT INTO emp_permission VALUES("61","Loan=172643433","Missed Payment","0","0","0","0");
INSERT INTO emp_permission VALUES("62","Loan=172643433","Payment","1","0","0","0");
INSERT INTO emp_permission VALUES("63","Loan=172643433","Employee Details","0","0","0","0");
INSERT INTO emp_permission VALUES("64","Loan=172643433","Module Permission","0","0","0","0");
INSERT INTO emp_permission VALUES("65","Loan=172643433","Savings Account","1","0","0","0");
INSERT INTO emp_permission VALUES("66","Loan=172643433","General Settings","0","0","0","0");
INSERT INTO emp_permission VALUES("67","Loan=176520691","Email Panel","1","1","0","0");
INSERT INTO emp_permission VALUES("68","Loan=176520691","Borrower Details","0","0","0","0");
INSERT INTO emp_permission VALUES("69","Loan=176520691","Employee Wallet","0","0","0","0");
INSERT INTO emp_permission VALUES("70","Loan=176520691","Loan Details","1","1","0","0");
INSERT INTO emp_permission VALUES("71","Loan=176520691","Internal Message","1","1","0","0");
INSERT INTO emp_permission VALUES("72","Loan=176520691","Missed Payment","0","0","0","0");
INSERT INTO emp_permission VALUES("73","Loan=176520691","Payment","1","1","0","0");
INSERT INTO emp_permission VALUES("74","Loan=176520691","Employee Details","0","0","0","0");
INSERT INTO emp_permission VALUES("75","Loan=176520691","Module Permission","0","0","0","0");
INSERT INTO emp_permission VALUES("76","Loan=176520691","Savings Account","0","0","0","0");
INSERT INTO emp_permission VALUES("77","Loan=176520691","General Settings","0","0","0","0");
INSERT INTO emp_permission VALUES("79","Loan=21319580","Campaign Section","1","1","1","1");


DROP TABLE IF EXISTS emp_role;

CREATE TABLE `emp_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS etemplates;

CREATE TABLE `etemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(200) NOT NULL,
  `receiver_email` varchar(350) NOT NULL,
  `subject` varchar(350) NOT NULL,
  `msg` text NOT NULL,
  `time_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS events;

CREATE TABLE `events` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `eday` varchar(3) NOT NULL,
  `emonth` varchar(10) NOT NULL,
  `from_time` varchar(10) NOT NULL,
  `to_time` varchar(10) NOT NULL,
  `location` varchar(100) NOT NULL,
  `plan` varchar(20) NOT NULL,
  `etitle` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS expense_document;

CREATE TABLE `expense_document` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `expid` varchar(50) NOT NULL,
  `newfilepath` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

INSERT INTO expense_document VALUES("6","EXP320410","img/1526367410_CMV_Confirmation_letter.docx");
INSERT INTO expense_document VALUES("5","EXP320410","img/1526367410_CLOUD COMPUTING - SEMINAR.docx");
INSERT INTO expense_document VALUES("4","EXP320410","img/1526367410_CIRCULAR.docx");
INSERT INTO expense_document VALUES("7","EXP524634","img/1526368597_appointment letter.pdf");
INSERT INTO expense_document VALUES("8","EXP524634","img/1526368597_cryptolife user guild.pdf");


DROP TABLE IF EXISTS expenses;

CREATE TABLE `expenses` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `branchid` varchar(200) NOT NULL,
  `expid` varchar(50) NOT NULL,
  `exptype` varchar(300) NOT NULL,
  `eamt` varchar(200) NOT NULL,
  `edate` varchar(15) NOT NULL,
  `edesc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO expenses VALUES("4","","EXP320410","SMS Units","500","2018-05-14","Payment for SMS Units");
INSERT INTO expenses VALUES("5","","EXP524634","Computer Software","350","2018-05-15","   Payment for software program  ");


DROP TABLE IF EXISTS exptype;

CREATE TABLE `exptype` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `etype` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

INSERT INTO exptype VALUES("1","Accommodation");
INSERT INTO exptype VALUES("2","Advertising and Promotion");
INSERT INTO exptype VALUES("3","Bank/Finance Charges");
INSERT INTO exptype VALUES("4","Computer Hardware");
INSERT INTO exptype VALUES("5","Computer Software");
INSERT INTO exptype VALUES("6","Insurance");
INSERT INTO exptype VALUES("7","Office Equipment");
INSERT INTO exptype VALUES("8","Office Rent");
INSERT INTO exptype VALUES("9","Printing");
INSERT INTO exptype VALUES("10","SMS Units");
INSERT INTO exptype VALUES("11","Travel");
INSERT INTO exptype VALUES("12","Miscellaneous");


DROP TABLE IF EXISTS faqs;

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO faqs VALUES("1","Please type the subject here","<p>Please Update Faqs Here</p>\n\n");


DROP TABLE IF EXISTS fin_info;

CREATE TABLE `fin_info` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `occupation` text NOT NULL,
  `mincome` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS footer;

CREATE TABLE `footer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `pho` varchar(200) NOT NULL,
  `face` varchar(200) NOT NULL,
  `webs` varchar(200) NOT NULL,
  `conh` varchar(200) NOT NULL,
  `twi` varchar(200) NOT NULL,
  `gplus` varchar(200) NOT NULL,
  `ins` varchar(200) NOT NULL,
  `yous` varchar(200) NOT NULL,
  `about` text NOT NULL,
  `apply` text NOT NULL,
  `mission` text NOT NULL,
  `objective` text NOT NULL,
  `map` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO footer VALUES("2","info@bequesters.com","+233808883675466","www.facebook.com/info@bequesters","www.bequesters.com","Lasvegas USA","www.twitter.com/info@bequesters","www.googleplus.com/oinfo@bequesters","www.in.com/info@bequesters","www.youtube.com/info@bequesters","About the system here. Thanks, We are just testing the software and we discover that the software is errors free. Thanks once again.","Who may apply here. Thabnks","Mission here. Thanks","System OBJECTIVE HERE. Thanks","");


DROP TABLE IF EXISTS hiw;

CREATE TABLE `hiw` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `hiw` text NOT NULL,
  PRIMARY KEY (`hid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO hiw VALUES("1","<p>We Provide Loans For Individual, Coperate and Many</p>\n\n");


DROP TABLE IF EXISTS interest_calculator;

CREATE TABLE `interest_calculator` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `lid` varchar(100) NOT NULL,
  `amt_to_pay` varchar(100) NOT NULL,
  `int_rate` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO interest_calculator VALUES("7","LID-8875671","0","429");


DROP TABLE IF EXISTS loan_info;

CREATE TABLE `loan_info` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `lid` varchar(200) NOT NULL,
  `lproduct` varchar(50) NOT NULL,
  `borrower` varchar(200) NOT NULL,
  `baccount` varchar(200) NOT NULL,
  `desc` text NOT NULL,
  `amount` varchar(200) NOT NULL,
  `disbursed_by` varchar(50) NOT NULL,
  `date_release` varchar(200) NOT NULL,
  `agent` varchar(200) NOT NULL,
  `unumber` varchar(50) NOT NULL,
  `g_name` varchar(200) NOT NULL,
  `g_phone` varchar(200) NOT NULL,
  `g_address` text NOT NULL,
  `g_dob` varchar(50) NOT NULL,
  `g_bname` varchar(200) NOT NULL,
  `rela` varchar(200) NOT NULL,
  `g_image` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `remarks` text NOT NULL,
  `pay_date` varchar(200) NOT NULL,
  `interest_rate` varchar(20) NOT NULL,
  `amount_topay` varchar(200) NOT NULL,
  `balance` varchar(200) NOT NULL,
  `teller` varchar(200) NOT NULL,
  `remark` text NOT NULL,
  `upstatus` varchar(200) NOT NULL,
  `p_status` varchar(20) NOT NULL COMMENT 'PAID or UNPAID',
  `branchid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO loan_info VALUES("4","LID-8875671","Business Loan","2","0107742310","I need the loan to invest/boost my business","5000","","2018-05-08","Akin James","","Mrs. James Victoria","0830389848","Lagos, Nigeria","","","Coursin","img/beryl2.jpg","Approved","Investment","2019-05-09","3","5150","3863","Akin James","to be paid on monthly basis","Completed","PART-PAID","");


DROP TABLE IF EXISTS message;

CREATE TABLE `message` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `sender_id` varchar(200) NOT NULL,
  `sender_name` varchar(200) NOT NULL,
  `msg_to` varchar(200) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS mywallet;

CREATE TABLE `mywallet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(200) NOT NULL,
  `t_to` varchar(200) NOT NULL,
  `Amount` varchar(200) NOT NULL,
  `Desc` varchar(200) NOT NULL,
  `wtype` varchar(200) NOT NULL,
  `branchid` varchar(50) NOT NULL,
  `tdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

INSERT INTO mywallet VALUES("4","Loan=21319580","NIL","100000","add funds to wallet","debit","","2018-05-01 23:20:05");
INSERT INTO mywallet VALUES("9","Loan=21319580","Loan=176520691","3450","Transfer to Staff Akinwale Sunday","transfer","","2018-05-27 05:40:06");
INSERT INTO mywallet VALUES("10","Loan=21319580","NIL","250000","add fund","debit","","2018-05-27 05:41:24");


DROP TABLE IF EXISTS pay_schedule;

CREATE TABLE `pay_schedule` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `lid` varchar(50) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `schedule` varchar(200) NOT NULL,
  `balance` varchar(200) NOT NULL,
  `payment` varchar(200) NOT NULL,
  `status` varchar(20) NOT NULL COMMENT 'PAID or UNPAID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;

INSERT INTO pay_schedule VALUES("74","LID-8875671","4","0107742310","2018-06-09","4721","429","PAID");
INSERT INTO pay_schedule VALUES("75","LID-8875671","4","0107742310","2018-07-09","4292","429","PAID");
INSERT INTO pay_schedule VALUES("76","LID-8875671","4","0107742310","2018-08-09","3863","429","PAID");
INSERT INTO pay_schedule VALUES("77","LID-8875671","4","0107742310","2018-09-09","3434","429","UNPAID");
INSERT INTO pay_schedule VALUES("78","LID-8875671","4","0107742310","2018-10-09","3005","429","UNPAID");
INSERT INTO pay_schedule VALUES("79","LID-8875671","4","0107742310","2018-11-09","2576","429","UNPAID");
INSERT INTO pay_schedule VALUES("80","LID-8875671","4","0107742310","2018-12-09","2147","429","UNPAID");
INSERT INTO pay_schedule VALUES("81","LID-8875671","4","0107742310","2019-01-09","1718","429","UNPAID");
INSERT INTO pay_schedule VALUES("82","LID-8875671","4","0107742310","2019-02-09","1289","429","UNPAID");
INSERT INTO pay_schedule VALUES("83","LID-8875671","4","0107742310","2019-03-09","860","429","UNPAID");
INSERT INTO pay_schedule VALUES("84","LID-8875671","4","0107742310","2019-04-09","431","429","UNPAID");
INSERT INTO pay_schedule VALUES("85","LID-8875671","4","0107742310","2019-05-09","2","429","UNPAID");
INSERT INTO pay_schedule VALUES("88","LID-8875671","4","0107742310","2019-06-09","0","2","UNPAID");


DROP TABLE IF EXISTS payment_schedule;

CREATE TABLE `payment_schedule` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `lid` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `term` varchar(200) NOT NULL,
  `day` varchar(200) NOT NULL,
  `schedule` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO payment_schedule VALUES("2","LID-8875671","0107742310","Fund to be paid back on monthly basis for 1 year","Year","Monthly");


DROP TABLE IF EXISTS payments;

CREATE TABLE `payments` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tid` varchar(200) NOT NULL,
  `lid` varchar(200) NOT NULL,
  `account_no` varchar(200) NOT NULL,
  `customer` varchar(200) NOT NULL,
  `loan_bal` varchar(200) NOT NULL,
  `pay_date` varchar(200) NOT NULL,
  `amount_to_pay` varchar(200) NOT NULL,
  `remarks` text NOT NULL,
  `branchid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO payments VALUES("1","Loan=21319580","LID-8875671","0107742310","Akinade&nbsp;Ayodeji","4721","2018-05-09","429","demo payment","");
INSERT INTO payments VALUES("2","Loan=21319580","LID-8875671","0107742310","Akinade&nbsp;Ayodeji","4292","2018-05-11","429","paid","");
INSERT INTO payments VALUES("4","Loan=21319580","LID-8875671","0107742310","Akinade&nbsp;Ayodeji","3863","2018-05-14","429","paid","");


DROP TABLE IF EXISTS payroll;

CREATE TABLE `payroll` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `branchid` varchar(200) NOT NULL,
  `staff_id` varchar(100) NOT NULL,
  `bizname` varchar(350) NOT NULL,
  `pay_date` varchar(50) NOT NULL,
  `basic_pay` varchar(200) NOT NULL,
  `overtime` varchar(200) NOT NULL,
  `paid_leave` varchar(200) NOT NULL,
  `transport_allowance` varchar(200) NOT NULL,
  `medical_allowance` varchar(200) NOT NULL,
  `bonus` varchar(200) NOT NULL,
  `other_allowance` varchar(200) NOT NULL,
  `gross_amount` varchar(200) NOT NULL,
  `pension` varchar(200) NOT NULL,
  `health_insurance` varchar(200) NOT NULL,
  `unpaid_leave` varchar(200) NOT NULL,
  `tax_deduction` varchar(200) NOT NULL,
  `salary_loan` varchar(200) NOT NULL,
  `total_deduction` varchar(200) NOT NULL,
  `paid_amount` varchar(200) NOT NULL COMMENT 'Net Pay',
  `payment_method` varchar(250) NOT NULL,
  `bank_name` varchar(200) NOT NULL,
  `acctno` varchar(15) NOT NULL,
  `adesc` varchar(500) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO payroll VALUES("1","BR19157","484","International Micro-finance Organization  (IMON)","2018-05-18","2500","150","0","0","0","50","0","2700","0","250","0","100","1200","1550","1150","Transfer","Access","0056164488","Paid","Balance paid directly to your bank account.");
INSERT INTO payroll VALUES("2","BR19157","487","International Micro-finance Organization  (IMON)","2018-05-19","500","20","0","50","50","0","0","620","0","0","0","100","200","300","320","Bank Transfer","GTBank","0124357607","Balance Paid","The Remaining Balance has been fully paid.");
INSERT INTO payroll VALUES("3","","","","","","","","","","","","","","","","","","","","","","","","");


DROP TABLE IF EXISTS short_urls;

CREATE TABLE `short_urls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `long_url` varchar(255) NOT NULL,
  `short_code` varbinary(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `short_code` (`short_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO short_urls VALUES("3","http://127.0.0.1/newloan/application/generate_payslip.php?id=2","oqul");
INSERT INTO short_urls VALUES("4","http://127.0.0.1/newloan/application/generate_payslip.php?id=3","s52n");


DROP TABLE IF EXISTS sms;

CREATE TABLE `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_gateway` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `api` text NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO sms VALUES("1","CriTechSMS","admin","criTech::SMS@1993","http://critechglobal.com/mysms/components/com_spc/smsapi.php?","Activated");


DROP TABLE IF EXISTS sponsors;

CREATE TABLE `sponsors` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `org_image` varchar(200) NOT NULL,
  `org_name` varchar(250) NOT NULL,
  `org_link` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS systemset;

CREATE TABLE `systemset` (
  `sysid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `descr` text NOT NULL,
  `keyw` text NOT NULL,
  `footer` text NOT NULL,
  `abb` varchar(200) NOT NULL,
  `fax` text NOT NULL,
  `bcountry` varchar(100) NOT NULL,
  `currency` text NOT NULL,
  `website` text NOT NULL,
  `mobile` text NOT NULL,
  `dhotline` varchar(20) NOT NULL,
  `image` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `email` text NOT NULL,
  `map` text NOT NULL,
  `stamp` varchar(350) NOT NULL,
  `timezone` text NOT NULL,
  `sms_charges` varchar(200) NOT NULL,
  `withdrawal_fee` varchar(20) NOT NULL,
  `theme_color` varchar(20) NOT NULL,
  PRIMARY KEY (`sysid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO systemset VALUES("1","Advance Loan / Lending Management System with SMS Notification & Saving System for Micro Finance Bank","International Micro-finance Organization  (IMON)","IMON - Nonprofit, Crowdfunding, Charity & Micro-finance Organization","charity,crowdfunding,nonprofit,orphan,Poor,funding,fundrising,ngo,children","All rights reserved. 2017 (c)","IMON","23459","United States","$","https://www.critechglobal.com","1-888-717-IMON","1-888-717-IMON","../img/ass.png","1101 Connecticut Ave NW Suite 405 Washington DC 20036								","info@imonfund.com","<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3104.872333485419!2d-77.0418138850062!3d38.90403467956958!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89b7b7b9283523a9%3A0x9dea77ed2928191a!2s1101+Connecticut+Ave+NW+%23405%2C+Washington%2C+DC+20036%2C+USA!5e0!3m2!1sen!2sng!4v1524956810185\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>			","stamp.jpg","-12","25","10","red");


DROP TABLE IF EXISTS testimonies;

CREATE TABLE `testimonies` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `b_id` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS transaction;

CREATE TABLE `transaction` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `txid` varchar(200) NOT NULL,
  `t_type` varchar(200) NOT NULL COMMENT 'Deposit OR Withdraw',
  `acctno` varchar(200) NOT NULL,
  `transfer_to` varchar(200) NOT NULL,
  `fn` varchar(200) NOT NULL,
  `ln` varchar(200) NOT NULL,
  `email` varchar(300) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `branchid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

INSERT INTO transaction VALUES("1","TXID-55937683","Deposit","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","5","2018-04-17 03:16:25","");
INSERT INTO transaction VALUES("2","TXID-96835816","Withdraw","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","3","2018-04-17 03:16:37","");
INSERT INTO transaction VALUES("3","TXID-79381958","Withdraw","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","2","2018-04-17 03:16:45","");
INSERT INTO transaction VALUES("4","TXID-42491394","Deposit","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","10","2018-04-17 03:16:53","");
INSERT INTO transaction VALUES("5","TXID-74638672","Withdraw","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","5","2018-04-17 03:17:00","");
INSERT INTO transaction VALUES("6","TXID-15410400","Deposit","0107742310","----","Akinade","Ayodeji","critech.getsolution@yahoo.com","08057102359","50000","2018-04-17 03:17:08","");
INSERT INTO transaction VALUES("7","TXID-12078735","Deposit","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","150000","2018-04-17 03:17:15","");
INSERT INTO transaction VALUES("8","TXID-38809814","Withdraw","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","2500","2018-04-17 03:17:22","");
INSERT INTO transaction VALUES("9","TXID-51565247","Withdraw","0107742310","----","Akinade","Ayodeji","critech.getsolution@yahoo.com","08057102359","1800","2018-04-17 03:17:31","");
INSERT INTO transaction VALUES("14","TXID-68376099","Withdraw-Charges","0107742310","----","Akinade","Ayodeji","critech.getsolution@yahoo.com","08057102359","10","2018-04-17 03:17:38","");
INSERT INTO transaction VALUES("15","TXID-68376099","Withdraw","0107742310","----","Akinade","Ayodeji","critech.getsolution@yahoo.com","08057102359","1150","2018-04-17 03:17:51","");
INSERT INTO transaction VALUES("16","TXID-82851197","Withdraw","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","1450","2018-04-17 03:17:59","");
INSERT INTO transaction VALUES("17","TXID-86981385","Transfer","0107742310","0102460083","Akinade","Ayodeji","critech.getsolution@yahoo.com","08057102359","1250","2018-04-17 03:05:41","");
INSERT INTO transaction VALUES("18","TXID-86981385","Transfer-Received","0102460083","0107742310","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","1250","2018-04-17 03:24:07","");
INSERT INTO transaction VALUES("19","TXID-21807556","Transfer","0102460083","0107742310","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","1000","2018-04-17 02:48:23","");
INSERT INTO transaction VALUES("20","TXID-21807556","Transfer-Received","0107742310","0102460083","Akinade","Ayodeji","critech.getsolution@yahoo.com","08057102359","1000","2018-04-17 02:48:23","");
INSERT INTO transaction VALUES("21","TXID-43756470","Transfer","0107742310","0102460083","Akinade","Ayodeji","critech.getsolution@yahoo.com","08057102359","50.55","2018-04-17 03:11:43","");
INSERT INTO transaction VALUES("22","TXID-43756470","Transfer-Received","0102460083","0107742310","Olatunde","Timilehin","timilehingbemi@gmail.com","08057102359","50.55","2018-04-17 03:11:43","");
INSERT INTO transaction VALUES("23","TXID-25697980","Deposit","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08101750845","250","2018-05-28 01:46:04","");
INSERT INTO transaction VALUES("24","TXID-37668929","Deposit","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08101750845","200","2018-05-28 01:52:29","");
INSERT INTO transaction VALUES("25","TXID-72193756","Deposit","0107742310","----","Akinade","Ayodeji","critech.getsolution@yahoo.com","09057102359","150","2018-05-28 01:56:27","");
INSERT INTO transaction VALUES("26","TXID-12181784","Deposit","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08101750845","100","2018-05-28 01:56:42","");
INSERT INTO transaction VALUES("27","TXID-47828462","Deposit","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08101750845","120","2018-05-28 02:02:31","");
INSERT INTO transaction VALUES("28","TXID-83105027","Deposit","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08101750845","50","2018-05-28 02:10:07","");
INSERT INTO transaction VALUES("29","TXID-49038192","Deposit","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08101750845","450","2018-05-28 02:12:29","");
INSERT INTO transaction VALUES("30","TXID-92872538","Deposit","0102460083","----","Olatunde","Timilehin","timilehingbemi@gmail.com","08101750845","135","2018-05-28 02:24:44","");


DROP TABLE IF EXISTS twallet;

CREATE TABLE `twallet` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tid` varchar(200) NOT NULL,
  `Total` varchar(200) NOT NULL,
  `branchid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO twallet VALUES("1","Loan=21319580","346550","");
INSERT INTO twallet VALUES("3","Loan=176520691","3450","");


DROP TABLE IF EXISTS user;

CREATE TABLE `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `addr1` text NOT NULL,
  `addr2` text NOT NULL,
  `city` varchar(200) NOT NULL,
  `state` varchar(200) NOT NULL,
  `zip` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `id` varchar(200) NOT NULL,
  `image` text NOT NULL,
  `role` varchar(200) NOT NULL,
  `branchid` varchar(100) NOT NULL,
  `utype` varchar(200) NOT NULL COMMENT 'Registered OR Unregistered',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=488 DEFAULT CHARSET=latin1;

INSERT INTO user VALUES("482","Akin James","critech.getresponse@gmail.com","08101750845","address1","address2","city","state","zip","US","comment","admin","YWRtaW4=","Loan=21319580","img/logo1.jpg","super-admin","","Registered");
INSERT INTO user VALUES("483","AKINADE AYODEJI","critech.getsolution@yahoo.com","08123234876","demo","demo","demop","demo","12345","US","","staff1","c3RhZmYx","Loan=172643433","img/EMP.png","staff","BR19157","Registered");
INSERT INTO user VALUES("484","Akinwale Sunday","sanjeevdotasara@gmail.com","08057102359","test","test","test","test","18373","US","","staff2","c3RhZmYy","Loan=176520691","img/IMG-20170906-WA0008.jpg","staff","BR19157","Registered");
INSERT INTO user VALUES("487","Adeolu Johnson","adjohnson@gmail.com","08123234876","testing","testing again","Lagos","Lagos","23401","Nigeria","","","","Loan=214366760","","","BR19157","Unregistered");


DROP TABLE IF EXISTS video_advert;

CREATE TABLE `video_advert` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `campaign_title` varchar(200) NOT NULL,
  `campaign_url` varchar(500) NOT NULL,
  `campaign_desc` text NOT NULL,
  `status` varchar(20) NOT NULL COMMENT 'Normal or Featured',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS volunteer;

CREATE TABLE `volunteer` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `v_image` varchar(200) NOT NULL,
  `v_name` varchar(200) NOT NULL,
  `v_occupation` varchar(200) NOT NULL,
  `v_facebook` varchar(200) NOT NULL,
  `v_twitter` varchar(100) NOT NULL,
  `v_skype` varchar(100) NOT NULL,
  `v_youtube` varchar(100) NOT NULL,
  `v_motivation_words` text NOT NULL,
  `v_address` text NOT NULL,
  `v_email` varchar(200) NOT NULL,
  `v_phone` varchar(15) NOT NULL,
  `v_education` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `v_type` varchar(50) NOT NULL COMMENT 'Normal or Featured',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS vpost;

CREATE TABLE `vpost` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `image` varchar(200) NOT NULL,
  `headings` varchar(200) NOT NULL,
  `motivation_words` text NOT NULL,
  `vlink` varchar(200) NOT NULL,
  `action_btn` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'Show or Hide',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO vpost VALUES("1","../img/volunteer-make-a-difference.png","Become a Proud Volunteer","No one has ever become poor by giving. Join us today and make a difference today as there is no better reward than putting smile on a needy family.","campaign.php","GET INVOLVED NOW!","Show");


DROP TABLE IF EXISTS withdrawal_request;

CREATE TABLE `withdrawal_request` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `wid` varchar(100) NOT NULL,
  `w_details` text NOT NULL,
  `w_amount` varchar(200) NOT NULL,
  `w_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `wstatus` varchar(100) NOT NULL COMMENT 'Approved or Declined',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



