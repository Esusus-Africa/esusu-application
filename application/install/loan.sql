-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 14, 2018 at 07:37 AM
-- Server version: 5.7.21
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `loan`
--

-- --------------------------------------------------------

--
-- Table structure for table `aboutus`
--

CREATE TABLE `aboutus` (
  `abid` int(11) NOT NULL,
  `who_we_are` text NOT NULL,
  `mission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `activate_member`
--

CREATE TABLE `activate_member` (
  `id` int(20) NOT NULL,
  `url` varchar(500) NOT NULL,
  `shorturl` varbinary(6) NOT NULL,
  `attempt` varchar(4) NOT NULL,
  `acn` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `additional_fees`
--

CREATE TABLE `additional_fees` (
  `id` int(20) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `fee` varchar(200) NOT NULL,
  `Amount` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `additional_fees`
--

INSERT INTO `additional_fees` (`id`, `get_id`, `tid`, `fee`, `Amount`) VALUES
(4, '4', '0107742310', 'Late Payment', '50');

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE `attachment` (
  `id` int(20) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `attached_file` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attachment`
--

INSERT INTO `attachment` (`id`, `get_id`, `tid`, `attached_file`, `date_time`) VALUES
(5, '4', '2', 'document/7752_File_IMG-20180419-WA0001.jpg', '2018-05-02 06:10:42'),
(6, '4', '2', 'document/6401_File_bulksms.pdf', '2018-05-02 06:10:33'),
(7, '', '383', 'document/4694_File_3_25_151_126_25_25_4.png', '2018-08-17 14:30:53'),
(8, '8', '383', 'document/5874_File_My_National_ID_Card.jpeg', '2018-08-17 15:21:57'),
(11, '14', '383', 'document/7505_File_Transcript_2.pdf', '2018-08-24 18:29:44'),
(14, '17', '383', 'document/4366_File_Transcript.pdf', '2018-08-28 06:37:46');

-- --------------------------------------------------------

--
-- Table structure for table `authorized_card`
--

CREATE TABLE `authorized_card` (
  `id` int(20) NOT NULL,
  `refid` varchar(200) NOT NULL,
  `lid` varchar(200) NOT NULL,
  `acn` varchar(20) NOT NULL,
  `email` varchar(350) NOT NULL,
  `authorized_code` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `authorized_card`
--

INSERT INTO `authorized_card` (`id`, `refid`, `lid`, `acn`, `email`, `authorized_code`) VALUES
(1, 'T190513335854600', 'LID-8875671', '0107742310', 'critech.getsolution@yahoo.com', 'AUTH_9352u32j6r'),
(2, 'T711482038169142', 'LID-10662930', '105460083', 'timilehingbemi@gmail.com', 'AUTH_gifs62jutx'),
(3, 'T653041425102557', 'LID-90013493', '105460083', 'timilehingbemi@gmail.com', 'AUTH_frys82zme1'),
(5, 'T015806915686598', 'LID-96094509', '105460083', 'timilehingbemi@gmail.com', 'AUTH_7a3dwbsrlt'),
(6, 'T599705179653655', 'LID-21599195', '105460083', 'timilehingbemi@gmail.com', 'AUTH_s41p6wzdwi'),
(9, 'T332471546478539', 'LID-38339808', '105460083', 'timilehingbemi@gmail.com', 'AUTH_ffsr5u2tm3'),
(10, 'T855180765920437', 'LID-65582190', '0102460083', 'timilehingbemi@gmail.com', 'AUTH_uom0lt4m0o'),
(11, 'T796390113989372', 'LID-88770896', '0101501578', 'acpalace2016@gmail.com', 'AUTH_2rns3p1a1h');

-- --------------------------------------------------------

--
-- Table structure for table `backup`
--

CREATE TABLE `backup` (
  `id` int(200) NOT NULL,
  `tracking_id` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `banaid` int(11) NOT NULL,
  `bannar` text NOT NULL,
  `title` varchar(150) NOT NULL,
  `short_desc` varchar(600) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`banaid`, `bannar`, `title`, `short_desc`) VALUES
(1, '../banner/home1-3.jpg', 'SIGN THE PETITION NOW', 'Tell the World you prefer to stand with IMONFUND'),
(2, '../banner/heading-1.jpg', 'JOIN OUT MOVEMENT', 'We are Good in Building Better Communities Across the World'),
(3, '../banner/home.jpg', 'JOIN OUR MOVEMENT', 'Contribute and Support our Work Today to Improve this Nation');

-- --------------------------------------------------------

--
-- Table structure for table `battachment`
--

CREATE TABLE `battachment` (
  `id` int(20) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `attached_file` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bcountries`
--

CREATE TABLE `bcountries` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `alpha_2` varchar(200) NOT NULL DEFAULT '',
  `alpha_3` varchar(200) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bcountries`
--

INSERT INTO `bcountries` (`id`, `name`, `alpha_2`, `alpha_3`) VALUES
(1, 'Afghanistan', 'fl', 'afg'),
(2, 'Aland Islands', 'ax', 'ala'),
(3, 'Albania', 'al', 'alb'),
(4, 'Algeria', 'dz', 'dza'),
(5, 'American Samoa', 'as', 'asm'),
(6, 'Andorra', 'ad', 'and'),
(7, 'Angola', 'ao', 'ago'),
(8, 'Anguilla', 'ai', 'aia'),
(9, 'Antarctica', 'aq', 'ata'),
(10, 'Antigua and Barbuda', 'ag', 'atg'),
(11, 'Argentina', 'ar', 'arg'),
(12, 'Armenia', 'am', 'arm'),
(13, 'Aruba', 'aw', 'abw'),
(14, 'Australia', 'au', 'aus'),
(15, 'Austria', 'at', 'aut'),
(16, 'Azerbaijan', 'az', 'aze'),
(17, 'Bahamas', 'bs', 'bhs'),
(18, 'Bahrain', 'bh', 'bhr'),
(19, 'Bangladesh', 'bd', 'bgd'),
(20, 'Barbados', 'bb', 'brb'),
(21, 'Belarus', 'by', 'blr'),
(22, 'Belgium', 'be', 'bel'),
(23, 'Belize', 'bz', 'blz'),
(24, 'Benin', 'bj', 'ben'),
(25, 'Bermuda', 'bm', 'bmu'),
(26, 'Bhutan', 'bt', 'btn'),
(27, 'Bolivia, Plurinational State of', 'bo', 'bol'),
(28, 'Bonaire, Sint Eustatius and Saba', 'bq', 'bes'),
(29, 'Bosnia and Herzegovina', 'ba', 'bih'),
(30, 'Botswana', 'bw', 'bwa'),
(31, 'Bouvet Island', 'bv', 'bvt'),
(32, 'Brazil', 'br', 'bra'),
(33, 'British Indian Ocean Territory', 'io', 'iot'),
(34, 'Brunei Darussalam', 'bn', 'brn'),
(35, 'Bulgaria', 'bg', 'bgr'),
(36, 'Burkina Faso', 'bf', 'bfa'),
(37, 'Burundi', 'bi', 'bdi'),
(38, 'Cambodia', 'kh', 'khm'),
(39, 'Cameroon', 'cm', 'cmr'),
(40, 'Canada', 'ca', 'can'),
(41, 'Cape Verde', 'cv', 'cpv'),
(42, 'Cayman Islands', 'ky', 'cym'),
(43, 'Central African Republic', 'cf', 'caf'),
(44, 'Chad', 'td', 'tcd'),
(45, 'Chile', 'cl', 'chl'),
(46, 'China', 'cn', 'chn'),
(47, 'Christmas Island', 'cx', 'cxr'),
(48, 'Cocos (Keeling) Islands', 'cc', 'cck'),
(49, 'Colombia', 'co', 'col'),
(50, 'Comoros', 'km', 'com'),
(51, 'Congo', 'cg', 'cog'),
(52, 'Congo, The Democratic Republic of the', 'cd', 'cod'),
(53, 'Cook Islands', 'ck', 'cok'),
(54, 'Costa Rica', 'cr', 'cri'),
(55, 'Cote d\'Ivoire', 'ci', 'civ'),
(56, 'Croatia', 'hr', 'hrv'),
(57, 'Cuba', 'cu', 'cub'),
(58, 'Curacao', 'cw', 'cuw'),
(59, 'Cyprus', 'cy', 'cyp'),
(60, 'Czech Republic', 'cz', 'cze'),
(61, 'Denmark', 'dk', 'dnk'),
(62, 'Djibouti', 'dj', 'dji'),
(63, 'Dominica', 'dm', 'dma'),
(64, 'Dominican Republic', 'do', 'dom'),
(65, 'Ecuador', 'ec', 'ecu'),
(66, 'Egypt', 'eg', 'egy'),
(67, 'El Salvador', 'sv', 'slv'),
(68, 'Equatorial Guinea', 'gq', 'gnq'),
(69, 'Eritrea', 'er', 'eri'),
(70, 'Estonia', 'ee', 'est'),
(71, 'Ethiopia', 'et', 'eth'),
(72, 'Falkland Islands (Malvinas)', 'fk', 'flk'),
(73, 'Faroe Islands', 'fo', 'fro'),
(74, 'Fiji', 'fj', 'fji'),
(75, 'Finland', 'fi', 'fin'),
(76, 'France', 'fr', 'fra'),
(77, 'French Guiana', 'gf', 'guf'),
(78, 'French Polynesia', 'pf', 'pyf'),
(79, 'French Southern Territories', 'tf', 'atf'),
(80, 'Gabon', 'ga', 'gab'),
(81, 'Gambia', 'gm', 'gmb'),
(82, 'Georgia', 'ge', 'geo'),
(83, 'Germany', 'de', 'deu'),
(84, 'Ghana', 'gh', 'gha'),
(85, 'Gibraltar', 'gi', 'gib'),
(86, 'Greece', 'gr', 'grc'),
(87, 'Greenland', 'gl', 'grl'),
(88, 'Grenada', 'gd', 'grd'),
(89, 'Guadeloupe', 'gp', 'glp'),
(90, 'Guam', 'gu', 'gum'),
(91, 'Guatemala', 'gt', 'gtm'),
(92, 'Guernsey', 'gg', 'ggy'),
(93, 'Guinea', 'gn', 'gin'),
(94, 'Guinea-Bissau', 'gw', 'gnb'),
(95, 'Guyana', 'gy', 'guy'),
(96, 'Haiti', 'ht', 'hti'),
(97, 'Heard Island and McDonald Islands', 'hm', 'hmd'),
(98, 'Holy See (Vatican City State)', 'va', 'vat'),
(99, 'Honduras', 'hn', 'hnd'),
(100, 'Hong Kong', 'hk', 'hkg'),
(101, 'Hungary', 'hu', 'hun'),
(102, 'Iceland', 'is', 'isl'),
(103, 'India', 'in', 'ind'),
(104, 'Indonesia', 'id', 'idn'),
(105, 'Iran, Islamic Republic of', 'ir', 'irn'),
(106, 'Iraq', 'iq', 'irq'),
(107, 'Ireland', 'ie', 'irl'),
(108, 'Isle of Man', 'im', 'imn'),
(109, 'Israel', 'il', 'isr'),
(110, 'Italy', 'it', 'ita'),
(111, 'Jamaica', 'jm', 'jam'),
(112, 'Japan', 'jp', 'jpn'),
(113, 'Jersey', 'je', 'jey'),
(114, 'Jordan', 'jo', 'jor'),
(115, 'Kazakhstan', 'kz', 'kaz'),
(116, 'Kenya', 'ke', 'ken'),
(117, 'Kiribati', 'ki', 'kir'),
(118, 'Korea, Democratic People\'s Republic of', 'kp', 'prk'),
(119, 'Korea, Republic of', 'kr', 'kor'),
(120, 'Kuwait', 'kw', 'kwt'),
(121, 'Kyrgyzstan', 'kg', 'kgz'),
(122, 'Lao People\'s Democratic Republic', 'la', 'lao'),
(123, 'Latvia', 'lv', 'lva'),
(124, 'Lebanon', 'lb', 'lbn'),
(125, 'Lesotho', 'ls', 'lso'),
(126, 'Liberia', 'lr', 'lbr'),
(127, 'Libyan Arab Jamahiriya', 'ly', 'lby'),
(128, 'Liechtenstein', 'li', 'lie'),
(129, 'Lithuania', 'lt', 'ltu'),
(130, 'Luxembourg', 'lu', 'lux'),
(131, 'Macao', 'mo', 'mac'),
(132, 'Macedonia, The former Yugoslav Republic of', 'mk', 'mkd'),
(133, 'Madagascar', 'mg', 'mdg'),
(134, 'Malawi', 'mw', 'mwi'),
(135, 'Malaysia', 'my', 'mys'),
(136, 'Maldives', 'mv', 'mdv'),
(137, 'Mali', 'ml', 'mli'),
(138, 'Malta', 'mt', 'mlt'),
(139, 'Marshall Islands', 'mh', 'mhl'),
(140, 'Martinique', 'mq', 'mtq'),
(141, 'Mauritania', 'mr', 'mrt'),
(142, 'Mauritius', 'mu', 'mus'),
(143, 'Mayotte', 'yt', 'myt'),
(144, 'Mexico', 'mx', 'mex'),
(145, 'Micronesia, Federated States of', 'fm', 'fsm'),
(146, 'Moldova, Republic of', 'md', 'mda'),
(147, 'Monaco', 'mc', 'mco'),
(148, 'Mongolia', 'mn', 'mng'),
(149, 'Montenegro', 'me', 'mne'),
(150, 'Montserrat', 'ms', 'msr'),
(151, 'Morocco', 'ma', 'mar'),
(152, 'Mozambique', 'mz', 'moz'),
(153, 'Myanmar', 'mm', 'mmr'),
(154, 'Namibia', 'na', 'nam'),
(155, 'Nauru', 'nr', 'nru'),
(156, 'Nepal', 'np', 'npl'),
(157, 'Netherlands', 'nl', 'nld'),
(158, 'New Caledonia', 'nc', 'ncl'),
(159, 'New Zealand', 'nz', 'nzl'),
(160, 'Nicaragua', 'ni', 'nic'),
(161, 'Niger', 'ne', 'ner'),
(162, 'Nigeria', 'ng', 'nga'),
(163, 'Niue', 'nu', 'niu'),
(164, 'Norfolk Island', 'nf', 'nfk'),
(165, 'Northern Mariana Islands', 'mp', 'mnp'),
(166, 'Norway', 'no', 'nor'),
(167, 'Oman', 'om', 'omn'),
(168, 'Pakistan', 'pk', 'pak'),
(169, 'Palau', 'pw', 'plw'),
(170, 'Palestinian Territory, Occupied', 'ps', 'pse'),
(171, 'Panama', 'pa', 'pan'),
(172, 'Papua New Guinea', 'pg', 'png'),
(173, 'Paraguay', 'py', 'pry'),
(174, 'Peru', 'pe', 'per'),
(175, 'Philippines', 'ph', 'phl'),
(176, 'Pitcairn', 'pn', 'pcn'),
(177, 'Poland', 'pl', 'pol'),
(178, 'Portugal', 'pt', 'prt'),
(179, 'Puerto Rico', 'pr', 'pri'),
(180, 'Qatar', 'qa', 'qat'),
(181, 'Reunion', 're', 'reu'),
(182, 'Romania', 'ro', 'rou'),
(183, 'Russian Federation', 'ru', 'rus'),
(184, 'Rwanda', 'rw', 'rwa'),
(185, 'Saint Barthelemy', 'bl', 'blm'),
(186, 'Saint Helena, Ascension and Tristan Da Cunha', 'sh', 'shn'),
(187, 'Saint Kitts and Nevis', 'kn', 'kna'),
(188, 'Saint Lucia', 'lc', 'lca'),
(189, 'Saint Martin (French Part)', 'mf', 'maf'),
(190, 'Saint Pierre and Miquelon', 'pm', 'spm'),
(191, 'Saint Vincent and The Grenadines', 'vc', 'vct'),
(192, 'Samoa', 'ws', 'wsm'),
(193, 'San Marino', 'sm', 'smr'),
(194, 'Sao Tome and Principe', 'st', 'stp'),
(195, 'Saudi Arabia', 'sa', 'sau'),
(196, 'Senegal', 'sn', 'sen'),
(197, 'Serbia', 'rs', 'srb'),
(198, 'Seychelles', 'sc', 'syc'),
(199, 'Sierra Leone', 'sl', 'sle'),
(200, 'Singapore', 'sg', 'sgp'),
(201, 'Sint Maarten (Dutch Part)', 'sx', 'sxm'),
(202, 'Slovakia', 'sk', 'svk'),
(203, 'Slovenia', 'si', 'svn'),
(204, 'Solomon Islands', 'sb', 'slb'),
(205, 'Somalia', 'so', 'som'),
(206, 'South Africa', 'za', 'zaf'),
(207, 'South Georgia and The South Sandwich Islands', 'gs', 'sgs'),
(208, 'South Sudan', 'ss', 'ssd'),
(209, 'Spain', 'es', 'esp'),
(210, 'Sri Lanka', 'lk', 'lka'),
(211, 'Sudan', 'sd', 'sdn'),
(212, 'Suriname', 'sr', 'sur'),
(213, 'Svalbard and Jan Mayen', 'sj', 'sjm'),
(214, 'Swaziland', 'sz', 'swz'),
(215, 'Sweden', 'se', 'swe'),
(216, 'Switzerland', 'ch', 'che'),
(217, 'Syrian Arab Republic', 'sy', 'syr'),
(218, 'Taiwan, Province of China', 'tw', 'twn'),
(219, 'Tajikistan', 'tj', 'tjk'),
(220, 'Tanzania, United Republic of', 'tz', 'tza'),
(221, 'Thailand', 'th', 'tha'),
(222, 'Timor-Leste', 'tl', 'tls'),
(223, 'Togo', 'tg', 'tgo'),
(224, 'Tokelau', 'tk', 'tkl'),
(225, 'Tonga', 'to', 'ton'),
(226, 'Trinidad and Tobago', 'tt', 'tto'),
(227, 'Tunisia', 'tn', 'tun'),
(228, 'Turkey', 'tr', 'tur'),
(229, 'Turkmenistan', 'tm', 'tkm'),
(230, 'Turks and Caicos Islands', 'tc', 'tca'),
(231, 'Tuvalu', 'tv', 'tuv'),
(232, 'Uganda', 'ug', 'uga'),
(233, 'Ukraine', 'ua', 'ukr'),
(234, 'United Arab Emirates', 'ae', 'are'),
(235, 'United Kingdom', 'gb', 'gbr'),
(236, 'United States', 'us', 'usa'),
(237, 'United States Minor Outlying Islands', 'um', 'umi'),
(238, 'Uruguay', 'uy', 'ury'),
(239, 'Uzbekistan', 'uz', 'uzb'),
(240, 'Vanuatu', 'vu', 'vut'),
(241, 'Venezuela, Bolivarian Republic of', 've', 'ven'),
(242, 'Viet Nam', 'vn', 'vnm'),
(243, 'Virgin Islands, British', 'vg', 'vgb'),
(244, 'Virgin Islands, U.S.', 'vi', 'vir'),
(245, 'Wallis and Futuna', 'wf', 'wlf'),
(246, 'Western Sahara', 'eh', 'esh'),
(247, 'Yemen', 'ye', 'yem'),
(248, 'Zambia', 'zm', 'zmb'),
(249, 'Zimbabwe', 'zw', 'zwe');

-- --------------------------------------------------------

--
-- Table structure for table `borrowers`
--

CREATE TABLE `borrowers` (
  `id` int(11) NOT NULL,
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
  `acct_status` varchar(50) NOT NULL COMMENT 'Activated OR Not-Activated',
  `referral` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `borrowers`
--

INSERT INTO `borrowers` (`id`, `title`, `fname`, `lname`, `email`, `phone`, `gender`, `dob`, `wstatus`, `unumber`, `bizname`, `addrs1`, `addrs2`, `city`, `state`, `zip`, `country`, `community_role`, `posts`, `account`, `balance`, `image`, `date_time`, `last_withdraw_date`, `status`, `lofficer`, `c_sign`, `branchid`, `acct_status`, `referral`) VALUES
(1, '', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '', '', '', '', '', 'No. 17, Araromi Estate,', 'Ikeja Lagos.', 'Lagos', 'Lagos', '23401', 'Nigeria', 'Borrower', '0', '0102460083', '141007.55', 'img/imtimi.jpg', '2018-09-08 15:05:45', '2018-08-10', 'Completed', '', 'img/sign1.png', '', 'Activated', ''),
(2, '', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '09057102359', '', '', '', '', '', 'No. 138, Akano Ifelagba Street', 'Off Olunde, Ibadan', 'Ibadan', 'Oyo', '23402', 'Nigeria', 'Borrower', '0', '0107742310', '21148.45', 'img/ay1.jpg', '2018-08-06 17:02:25', '2018-08-06', 'Completed', '', 'img/sign.png', '', 'Activated', ''),
(4, 'Mrs.', 'Roseline', 'Aderemi', 'roseliner39@gmail.com', '79998000', 'Male', '1993-10-31', 'Student', '8874778478', '7nknlk', 'Ijebu Ode, Ogun State', 'Ijebu Ode, Ogun State', 'Ijebu Ode', 'Ogun', '23403', 'Nigeria', '', '0', '0103800879', '0.0', 'img/CloudHosting.jpg', '2018-07-03 08:43:47', '0000-00-00', 'Completed', '', 'img/sign.png', 'BR19157', 'Activated', ''),
(7, 'Mr.', 'demo', 'demo', 'acpalace2016@gmail.com', '08101750845', 'Male', '1993-10-31', 'Owner', '8874778478', 'demo business', 'Ijebu Ode, Ogun State', 'Ijebu Ode, Ogun State', 'Ijebu Ode', 'Ogun', '23403', 'Nigeria', 'Borrower', '0', '0101501578', '3340.00', 'img/2_29_146_146_0_26_0.png', '2018-09-07 08:47:19', '2018-08-06', 'Completed', '', 'img/payment-button.png', 'BR19157', 'Activated', ''),
(383, 'Mr.', 'Adebayo', 'Oluwadamilare', 'timilehingbemi@gmail.com', '8123234876', 'Male', '1993-10-31', 'Student', '9887797799', 'Electric Expert', 'No. 17, Araromi Estate,', 'Ikeja Lagos.', 'Lagos', 'Lagos', '23401', 'Nigeria', 'Borrower', '', '105460083', '500', '', '2018-09-07 12:21:56', '0000-00-00', 'Completed', '', '', '', 'Activated', '');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(20) NOT NULL,
  `bname` varchar(200) NOT NULL,
  `bopendate` varchar(100) NOT NULL,
  `bcountry` char(100) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `c_rate` varchar(200) NOT NULL,
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
  `bstatus` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `bname`, `bopendate`, `bcountry`, `currency`, `c_rate`, `branch_addrs`, `branch_city`, `branch_province`, `branch_zipcode`, `branch_landline`, `branch_mobile`, `minloan_amount`, `maxloan_amount`, `min_interest_rate`, `max_interest_rate`, `branchid`, `bstatus`) VALUES
(16, 'Ikeja Branch, Lagos.', '2018-05-14', 'Nigeria', '$', '1', 'Ijebu Ode, Ogun State', 'Ijebu Ode', 'Ogun', '23403', '0089', '0289837838', '10', '5000000', '5', '20', 'BR19157', 'Operational'),
(17, 'Challenge Branch, Ibadan.', '2018-06-18', 'Nigeria', '$', '1', 'Challenge, Along Ring-road', 'Ibadan', 'Oyo', '23402', '08082938838', '09057102359', '100', '10000000', '5', '20', 'BR44999', 'Operational');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_cat`
--

CREATE TABLE `campaign_cat` (
  `id` int(20) NOT NULL,
  `c_category` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `campaign_cat`
--

INSERT INTO `campaign_cat` (`id`, `c_category`) VALUES
(6, 'Women Business Projects'),
(7, 'Educational & Student Innovation'),
(9, 'Community Empowerment ');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_lendpay_history`
--

CREATE TABLE `campaign_lendpay_history` (
  `id` int(20) NOT NULL,
  `tid` varchar(50) NOT NULL,
  `lpay_id` varchar(50) NOT NULL,
  `pid` varchar(50) NOT NULL,
  `c_id` varchar(50) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `lender_name` varchar(200) NOT NULL,
  `lender_email` varchar(200) NOT NULL,
  `pdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expected_pdate` varchar(50) NOT NULL,
  `lstatus` varchar(50) NOT NULL COMMENT 'Paid Or Pending',
  `disbursed_status` varchar(50) NOT NULL COMMENT 'Released Or Not-Released',
  `branchid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_pay_history`
--

CREATE TABLE `campaign_pay_history` (
  `id` int(20) NOT NULL,
  `pid` varchar(100) NOT NULL,
  `c_id` varchar(50) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `dtype` varchar(50) NOT NULL COMMENT 'Donate OR Lend',
  `pdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_to` varchar(50) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pstatus` varchar(100) NOT NULL COMMENT 'Completed or Fail or Pending',
  `wstatus` varchar(50) NOT NULL COMMENT 'released OR not-released',
  `branchid` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `causes`
--

CREATE TABLE `causes` (
  `id` int(20) NOT NULL,
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
  `branchid` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `causes`
--

INSERT INTO `causes` (`id`, `b_id`, `campaign_image`, `campaign_title`, `campaign_desc`, `current_fund`, `budget`, `total_contributer`, `campaign_type`, `c_category`, `campaign_fee`, `msg_to_donor`, `twitter_handler`, `location`, `dfrom`, `dto`, `campaign_status`, `tname`, `designation`, `aboutus`, `branchid`) VALUES
(1, '1', 'img/cs1.jpg', 'CriTect Educational Scholarship Project', '<p>CriTect Scholarship is going to be available for all student in tertiary instittion, colleges and the likes.</p>\r\n', '0.00', '3200.00', '0', 'Donation', 'Educational & Student Innovation', '3', 'Thanks for your contribution', '@smsteams1', 'Lagos, Nigeria', '2018-05-27', '2018-07-27', 'Disapproved', 'Akinade Ayodeji', 'CEO', 'We are No. 1 IT Industry in Nigeria.', '');

-- --------------------------------------------------------

--
-- Table structure for table `causes_note`
--

CREATE TABLE `causes_note` (
  `id` int(20) NOT NULL,
  `campaign_id` varchar(50) NOT NULL,
  `staffid` varchar(50) NOT NULL,
  `b_id` varchar(50) NOT NULL,
  `cstatus` varchar(20) NOT NULL,
  `cnote` text NOT NULL,
  `note_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `causes_note`
--

INSERT INTO `causes_note` (`id`, `campaign_id`, `staffid`, `b_id`, `cstatus`, `cnote`, `note_date`) VALUES
(3, '1', 'Loan=21319580', '1', 'Pending', 'Campaign waiting for approval from the concerned department, please wait...', '2018-06-18 10:27:49'),
(4, '1', 'Loan=21319580', '1', 'Disapproved', 'Sorry! The Campaign does not comply with our privacy policy, kindly re-adjust', '2018-06-18 10:17:51');

-- --------------------------------------------------------

--
-- Table structure for table `collateral`
--

CREATE TABLE `collateral` (
  `id` int(20) NOT NULL,
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
  `observation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `collateral`
--

INSERT INTO `collateral` (`id`, `idm`, `tid`, `name`, `type_of_collateral`, `model`, `make`, `serial_number`, `estimated_price`, `proof_of_ownership`, `cimage`, `observation`) VALUES
(3, '4', '0107742310', 'CriTech Global Enterprises', 'Company', 'BN 2535016', 'CAC', 'NIL', '20000', 'document/5508_File_copyrights_document_6a.pdf', 'document/Contract Agreement for USA Customers.pdf', 'Good condition');

-- --------------------------------------------------------

--
-- Table structure for table `deptors`
--

CREATE TABLE `deptors` (
  `id` int(20) NOT NULL,
  `lid` varchar(50) NOT NULL,
  `uaccount` varchar(20) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `lstatus` varchar(20) NOT NULL,
  `expire_date` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deptors`
--

INSERT INTO `deptors` (`id`, `lid`, `uaccount`, `amount`, `lstatus`, `expire_date`) VALUES
(2, 'LID-8875671', '0107742310', '5000', 'NotPaid', '2019-05-09'),
(3, 'LID-8875671', '0107742310', '5000', 'NotPaid', '2019-05-09'),
(4, 'LID-10662930', '105460083', '1000', 'NotPaid', '2018-08-17'),
(5, 'LID-10662930', '105460083', '1000', 'NotPaid', '2018-08-17'),
(6, 'LID-10662930', '105460083', '1000', 'NotPaid', '2018-08-17'),
(7, 'LID-90013493', '105460083', '500', 'NotPaid', '2018-08-17'),
(8, 'LID-90013493', '105460083', '500', 'NotPaid', '2018-08-17'),
(9, 'LID-90013493', '105460083', '500', 'NotPaid', '2018-08-17'),
(10, 'LID-90013493', '105460083', '500', 'NotPaid', '2018-08-17'),
(11, 'LID-21599195', '105460083', '2600', 'NotPaid', ''),
(12, 'LID-21599195', '105460083', '2600', 'NotPaid', ''),
(13, 'LID-21599195', '105460083', '2600', 'NotPaid', ''),
(14, 'LID-21599195', '105460083', '2600', 'NotPaid', ''),
(15, 'LID-21599195', '105460083', '2600', 'NotPaid', ''),
(16, 'LID-21599195', '105460083', '2600', 'NotPaid', ''),
(17, 'LID-21599195', '105460083', '2600', 'NotPaid', '2018-09-08'),
(18, 'LID-21599195', '105460083', '2600', 'NotPaid', '2018-09-08'),
(19, 'LID-21599195', '105460083', '2600', 'NotPaid', '2018-09-08'),
(20, 'LID-21599195', '105460083', '2600', 'NotPaid', '2018-09-08'),
(21, 'LID-38339808', '105460083', '12000', 'NotPaid', ''),
(22, 'LID-38339808', '105460083', '12000', 'NotPaid', '2018-08-30'),
(23, 'LID-38339808', '105460083', '12000', 'NotPaid', '2018-08-30'),
(24, 'LID-38339808', '105460083', '12000', 'NotPaid', '2018-08-30'),
(25, 'LID-38339808', '105460083', '12000', 'NotPaid', '2018-08-30'),
(26, 'LID-38339808', '105460083', '12000', 'NotPaid', '2018-08-30');

-- --------------------------------------------------------

--
-- Table structure for table `emp_permission`
--

CREATE TABLE `emp_permission` (
  `id` int(20) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `module_name` varchar(350) NOT NULL,
  `pcreate` varchar(20) NOT NULL,
  `pread` varchar(20) NOT NULL,
  `pupdate` varchar(20) NOT NULL,
  `pdelete` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emp_permission`
--

INSERT INTO `emp_permission` (`id`, `tid`, `module_name`, `pcreate`, `pread`, `pupdate`, `pdelete`) VALUES
(45, 'Loan=21319580', 'Branches', '1', '1', '1', '1'),
(46, 'Loan=21319580', 'Borrower Details', '1', '1', '1', '1'),
(47, 'Loan=21319580', 'Employee Wallet', '1', '1', '1', '1'),
(48, 'Loan=21319580', 'Loan Details', '1', '1', '1', '1'),
(49, 'Loan=21319580', 'Internal Message', '1', '1', '0', '0'),
(50, 'Loan=21319580', 'Missed Payment', '1', '1', '1', '1'),
(51, 'Loan=21319580', 'Payment', '1', '1', '1', '1'),
(52, 'Loan=21319580', 'Employee Details', '1', '1', '1', '1'),
(53, 'Loan=21319580', 'Module Permission', '1', '1', '1', '1'),
(54, 'Loan=21319580', 'Savings Account', '1', '1', '1', '1'),
(55, 'Loan=21319580', 'General Settings', '1', '1', '1', '0'),
(56, 'Loan=21319580', 'Expenses', '1', '1', '1', '1'),
(57, 'Loan=21319580', 'Payroll', '1', '1', '1', '1'),
(58, 'Loan=21319580', 'Collateral Registered', '1', '1', '1', '1'),
(59, 'Loan=21319580', 'Reports', '1', '1', '1', '1'),
(60, 'Loan=172643433', 'Internal Message', '1', '1', '0', '0'),
(61, 'Loan=172643433', 'Missed Payment', '0', '0', '0', '0'),
(62, 'Loan=172643433', 'Payment', '1', '0', '0', '0'),
(63, 'Loan=172643433', 'Employee Details', '0', '0', '0', '0'),
(64, 'Loan=172643433', 'Module Permission', '0', '0', '0', '0'),
(65, 'Loan=172643433', 'Savings Account', '1', '0', '0', '0'),
(66, 'Loan=172643433', 'General Settings', '0', '0', '0', '0'),
(67, 'Loan=176520691', 'Email Panel', '1', '1', '0', '0'),
(68, 'Loan=176520691', 'Borrower Details', '0', '0', '0', '0'),
(69, 'Loan=176520691', 'Employee Wallet', '0', '0', '0', '0'),
(70, 'Loan=176520691', 'Loan Details', '1', '1', '0', '0'),
(71, 'Loan=176520691', 'Internal Message', '1', '1', '0', '0'),
(72, 'Loan=176520691', 'Missed Payment', '0', '0', '0', '0'),
(73, 'Loan=176520691', 'Payment', '1', '1', '0', '0'),
(74, 'Loan=176520691', 'Employee Details', '0', '0', '0', '0'),
(75, 'Loan=176520691', 'Module Permission', '0', '0', '0', '0'),
(76, 'Loan=176520691', 'Savings Account', '0', '0', '0', '0'),
(77, 'Loan=176520691', 'General Settings', '0', '0', '0', '0'),
(79, 'Loan=21319580', 'Campaign Section', '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `emp_role`
--

CREATE TABLE `emp_role` (
  `id` int(11) NOT NULL,
  `role` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `etemplates`
--

CREATE TABLE `etemplates` (
  `id` int(11) NOT NULL,
  `sender` varchar(200) NOT NULL,
  `receiver_email` varchar(350) NOT NULL,
  `subject` varchar(350) NOT NULL,
  `msg` text NOT NULL,
  `time_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(20) NOT NULL,
  `eday` varchar(3) NOT NULL,
  `emonth` varchar(10) NOT NULL,
  `from_time` varchar(10) NOT NULL,
  `to_time` varchar(10) NOT NULL,
  `location` varchar(100) NOT NULL,
  `plan` varchar(20) NOT NULL,
  `etitle` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(20) NOT NULL,
  `branchid` varchar(200) NOT NULL,
  `expid` varchar(50) NOT NULL,
  `exptype` varchar(300) NOT NULL,
  `eamt` varchar(200) NOT NULL,
  `edate` varchar(15) NOT NULL,
  `edesc` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `branchid`, `expid`, `exptype`, `eamt`, `edate`, `edesc`) VALUES
(4, '', 'EXP320410', 'SMS Units', '500', '2018-05-14', 'Payment for SMS Units'),
(5, '', 'EXP524634', 'Computer Software', '350', '2018-05-15', '   Payment for software program  ');

-- --------------------------------------------------------

--
-- Table structure for table `expense_document`
--

CREATE TABLE `expense_document` (
  `id` int(20) NOT NULL,
  `expid` varchar(50) NOT NULL,
  `newfilepath` varchar(300) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expense_document`
--

INSERT INTO `expense_document` (`id`, `expid`, `newfilepath`) VALUES
(6, 'EXP320410', 'img/1526367410_CMV_Confirmation_letter.docx'),
(5, 'EXP320410', 'img/1526367410_CLOUD COMPUTING - SEMINAR.docx'),
(4, 'EXP320410', 'img/1526367410_CIRCULAR.docx'),
(7, 'EXP524634', 'img/1526368597_appointment letter.pdf'),
(8, 'EXP524634', 'img/1526368597_cryptolife user guild.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `exptype`
--

CREATE TABLE `exptype` (
  `id` int(20) NOT NULL,
  `etype` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exptype`
--

INSERT INTO `exptype` (`id`, `etype`) VALUES
(1, 'Accommodation'),
(2, 'Advertising and Promotion'),
(3, 'Bank/Finance Charges'),
(4, 'Computer Hardware'),
(5, 'Computer Software'),
(6, 'Insurance'),
(7, 'Office Equipment'),
(8, 'Office Rent'),
(9, 'Printing'),
(10, 'SMS Units'),
(11, 'Travel'),
(12, 'Miscellaneous');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `topic` text NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `topic`, `content`) VALUES
(1, 'Please type the subject here', '<p>Please Update Faqs Here</p>\n\n');

-- --------------------------------------------------------

--
-- Table structure for table `fin_info`
--

CREATE TABLE `fin_info` (
  `id` int(20) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `occupation` text NOT NULL,
  `mincome` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `footer`
--

CREATE TABLE `footer` (
  `id` int(11) NOT NULL,
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
  `map` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `footer`
--

INSERT INTO `footer` (`id`, `email`, `pho`, `face`, `webs`, `conh`, `twi`, `gplus`, `ins`, `yous`, `about`, `apply`, `mission`, `objective`, `map`) VALUES
(2, 'info@bequesters.com', '+233808883675466', 'www.facebook.com/info@bequesters', 'www.bequesters.com', 'Lasvegas USA', 'www.twitter.com/info@bequesters', 'www.googleplus.com/oinfo@bequesters', 'www.in.com/info@bequesters', 'www.youtube.com/info@bequesters', 'About the system here. Thanks, We are just testing the software and we discover that the software is errors free. Thanks once again.', 'Who may apply here. Thabnks', 'Mission here. Thanks', 'System OBJECTIVE HERE. Thanks', '');

-- --------------------------------------------------------

--
-- Table structure for table `hiw`
--

CREATE TABLE `hiw` (
  `hid` int(11) NOT NULL,
  `hiw` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hiw`
--

INSERT INTO `hiw` (`hid`, `hiw`) VALUES
(1, '<p>We Provide Loans For Individual, Coperate and Many</p>\n\n');

-- --------------------------------------------------------

--
-- Table structure for table `interest_calculator`
--

CREATE TABLE `interest_calculator` (
  `id` int(20) NOT NULL,
  `lid` varchar(100) NOT NULL,
  `amt_to_pay` varchar(100) NOT NULL,
  `int_rate` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `interest_calculator`
--

INSERT INTO `interest_calculator` (`id`, `lid`, `amt_to_pay`, `int_rate`) VALUES
(7, 'LID-8875671', '0', '429'),
(15, 'LID-9945699', '0', '13000'),
(16, 'LID-10662930', '0', '1300'),
(17, 'LID-90013493', '0', '525'),
(19, 'LID-96094509', '0', '20000'),
(30, 'LID-21599195', '0', '3600'),
(23, 'LID-38339808', '0', '12000'),
(29, 'LID-88770896', '0', '275'),
(31, 'LID-65582190', '0', '1200');

-- --------------------------------------------------------

--
-- Table structure for table `loan_info`
--

CREATE TABLE `loan_info` (
  `id` int(20) NOT NULL,
  `lid` varchar(200) NOT NULL,
  `lproduct` varchar(50) NOT NULL,
  `borrower` varchar(200) NOT NULL,
  `baccount` varchar(200) NOT NULL,
  `income` varchar(50) NOT NULL,
  `salary_date` varchar(50) NOT NULL,
  `employer` varchar(200) NOT NULL,
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
  `branchid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan_info`
--

INSERT INTO `loan_info` (`id`, `lid`, `lproduct`, `borrower`, `baccount`, `income`, `salary_date`, `employer`, `desc`, `amount`, `disbursed_by`, `date_release`, `agent`, `unumber`, `g_name`, `g_phone`, `g_address`, `g_dob`, `g_bname`, `rela`, `g_image`, `status`, `remarks`, `pay_date`, `interest_rate`, `amount_topay`, `balance`, `teller`, `remark`, `upstatus`, `p_status`, `branchid`) VALUES
(4, 'LID-8875671', 'Business Loan', '2', '0107742310', '', '', '', 'I need the loan to invest/boost my business', '5000', '', '2018-05-08', 'Akin James', '', 'Mrs. James Victoria', '0830389848', 'Lagos, Nigeria', '', '', 'Coursin', 'img/beryl2.jpg', 'Approved', 'Investment', '2019-05-09', '3', '5150', '860', 'Akin James', 'to be paid on monthly basis', 'Completed', 'PART-PAID', ''),
(6, 'LID-9945699', '2', '4', '0103800879', '', '', '', 'test', '10000', 'Online Transfer', '', 'Akin James', '88888888888', 'testing', '08101750845', 'test', '1993-12-09', 'test', 'father', 'img/404-cat.gif', 'Pending', 'test', '2018-09-11', '30', '13000', '13000', 'Akin James', 'test', 'Completed', 'UNPAID', ''),
(7, 'LID-10662930', '2', '383', '105460083', '', '', '', 'hhhhh', '1000', 'Cheque', '2018-08-17', 'Akin James', '22222228288', 'ggg', '886888787877', 'hhh', '2018-08-17', 'ggg', 'ghhhhh', 'img/404_page_cover.jpg', 'Approved', 'hhh', '2018-08-17', '30', '1300', '0', 'Akin James', 'hhhhh', 'Completed', 'PAID', ''),
(8, 'LID-90013493', '1', '383', '105460083', '', '', '', 'jdjj', '500', 'Wire Transfer', '2018-08-17', 'Akin James', '88888888888', 've', '34444', 'dwd', '2018-08-17', 'eve', 'ghhhhh', 'img/agruni.jpg', 'Approved', 'cee', '2018-08-17', '5', '525', '0', 'Akin James', 'cdee', 'Completed', 'PAID', ''),
(14, 'LID-21599195', '2', '383', '105460083', '6000', '2018-27-09', '', '', '3000', '', '2018-09-07', 'Akin James', '', 'testing', '08101750845', 'hhh', '', '', 'father', 'img/ay1.jpg', 'Approved', '', '2018-09-25', '30', '3600', '3600', 'Akin James', '', 'Completed', 'UNPAID', ''),
(17, 'LID-38339808', '2', '383', '105460083', '20000', '2018-08-27', '', '', '10000', '', '2018-08-28', 'Akin James', '', 'testing', '08101750845', 'ttte', '', '', 'father', 'img/ay1.jpg', 'Approved', '', '2018-08-30', '30', '12000', '12000', 'Akin James', '', 'Completed', 'UNPAID', ''),
(18, 'LID-65582190', '2', '1', '0102460083', '5000', '2018-08-30', '', 'test', '1000', '', '2018-09-07', 'Akin James', '', 'test', '08019199299', 'test', '', '', 'sister', 'img/9fdfcacaa4d943e0328bd32e35a40035ebdc7a9b.png', 'Pending', '', '2018-09-18', '30', '1200', '1200', 'Akin James', 'test', 'Completed', 'UNPAID', ''),
(23, 'LID-56686030', '2', '7', '0101501578', '5000', '2018-09-28', 'Gtext Media', 'ttii', '1000', '', '', '', '84588588588', 'test', '08101750845', 'test', '', '', 'sister', 'img/apple.jpg', 'Pending', '', '', '30', '', '', '', 'Rent', 'Pending', 'UNPAID', 'BR19157');

-- --------------------------------------------------------

--
-- Table structure for table `loan_product`
--

CREATE TABLE `loan_product` (
  `id` int(20) NOT NULL,
  `pname` varchar(250) NOT NULL,
  `interest` varchar(200) NOT NULL,
  `max_duration` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `loan_product`
--

INSERT INTO `loan_product` (`id`, `pname`, `interest`, `max_duration`) VALUES
(1, 'Workplace Loan', '5', '6'),
(2, 'Borrow-Me-Cash', '30', '1'),
(3, 'Salary Advance', '5', '6'),
(4, 'Business Improvement Loan', '5', '6'),
(5, 'Esusu Loan', '5', '6'),
(6, 'Group Loan', '5', '6'),
(7, 'Asset Loan', '5', '6');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(200) NOT NULL,
  `ticketid` varchar(50) NOT NULL,
  `sender_id` varchar(200) NOT NULL,
  `sender_name` varchar(200) NOT NULL,
  `receiver_id` varchar(50) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` text NOT NULL,
  `mstatus` varchar(50) NOT NULL COMMENT 'Pending OR Opened OR Closed',
  `branchid` varchar(50) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `ticketid`, `sender_id`, `sender_name`, `receiver_id`, `subject`, `message`, `mstatus`, `branchid`, `date_time`) VALUES
(6, 'IMO9799466', '1', 'Olatunde Timilehin', '', 'Why my Campaign get Disapproved?', '<p>Dear IMON Support,</p>\r\n\r\n<p>I just got a feedback today concerning my campaign but it was disapproved.<br />\r\nPlease i want your team to guide on how to get it corrected if i&#39;m making some mistake.<br />\r\n<br />\r\nRegards.<br />\r\nOlatunde.</p>\r\n', 'Opened', '', '2018-06-18 12:42:22'),
(7, 'IMO9799466', 'Loan=21319580', 'Akin James', '1', 'Why my Campaign get Disapproved? - Reply', '<p>Dear Olatunde,<br />\r\n<br />\r\nThanks for contacting IMON Fund.<br />\r\nPlease be informed that all instruction required is there on the campaign form.<br />\r\nEdit the campaign by filling the team field then re-apply for review.<br />\r\nThanks for your understanding.<br />\r\n<br />\r\nRegards<br />\r\nAkin,<br />\r\nIMON Cuctomer Support.<br />\r\nhttp://imonfund.com/</p>\r\n', 'Opened', '', '2018-06-18 12:09:25'),
(9, 'IMO9799466', '1', 'Olatunde Timilehin', 'Loan=21319580', 'Why my Campaign get Disapproved? - Reply - Reply', '<p>Thanks for your reply.<br />\r\nI&#39;ll go through it again.</p>\r\n', 'Opened', '', '2018-06-18 12:45:46'),
(10, 'IMO6624372', '7', 'demo demo', '', 'Test back', '<p>Hope it&#39;s working now sha?</p>\r\n', 'Pending', 'BR19157', '2018-09-08 20:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `mywallet`
--

CREATE TABLE `mywallet` (
  `id` int(11) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `t_to` varchar(200) NOT NULL,
  `Amount` varchar(200) NOT NULL,
  `Desc` varchar(200) NOT NULL,
  `wtype` varchar(200) NOT NULL,
  `branchid` varchar(50) NOT NULL,
  `tdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mywallet`
--

INSERT INTO `mywallet` (`id`, `tid`, `t_to`, `Amount`, `Desc`, `wtype`, `branchid`, `tdate`) VALUES
(4, 'Loan=21319580', 'NIL', '100000', 'add funds to wallet', 'debit', '', '2018-05-02 06:20:05'),
(9, 'Loan=21319580', 'Loan=176520691', '3450', 'Transfer to Staff Akinwale Sunday', 'transfer', 'BR19157', '2018-06-06 21:02:43'),
(10, 'Loan=21319580', 'NIL', '250000', 'add fund', 'debit', '', '2018-05-27 12:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(20) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `lid` varchar(200) NOT NULL,
  `refid` varchar(50) NOT NULL,
  `account_no` varchar(200) NOT NULL,
  `customer` varchar(200) NOT NULL,
  `loan_bal` varchar(200) NOT NULL,
  `pay_date` varchar(200) NOT NULL,
  `amount_to_pay` varchar(200) NOT NULL,
  `remarks` text NOT NULL,
  `branchid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tid`, `lid`, `refid`, `account_no`, `customer`, `loan_bal`, `pay_date`, `amount_to_pay`, `remarks`, `branchid`) VALUES
(1, 'Loan=21319580', 'LID-8875671', 'T357008326557708', '0107742310', 'Akinade&nbsp;Ayodeji', '4721', '2018-05-09', '429', 'paid', ''),
(2, 'Loan=21319580', 'LID-8875671', 'T357008326557009', '0107742310', 'Akinade&nbsp;Ayodeji', '4292', '2018-05-11', '429', 'paid', ''),
(4, 'Loan=21319580', 'LID-8875671', 'T357008326557223', '0107742310', 'Akinade&nbsp;Ayodeji', '3863', '2018-05-14', '429', 'paid', ''),
(17, 'Self', 'LID-8875671', 'T704125080801466', '0107742310', 'Akinade Ayodeji', '3434', '2018-07-03', '429', 'paid', ''),
(19, 'Self', 'LID-8875671', 'T442749182428906', '0107742310', 'Akinade Ayodeji', '3005', '2018-07-03', '429', 'paid', ''),
(20, 'Self', 'LID-8875671', 'T754844808279340', '0107742310', 'Akinade Ayodeji', '2576', '2018-07-03', '429', 'paid', ''),
(22, 'Self', 'LID-8875671', 'T339226627136009', '0107742310', 'Akinade Ayodeji', '2147', '2018-07-04', '429', 'paid', ''),
(23, 'Self', 'LID-8875671', 'T696206155047291', '0107742310', 'Akinade Ayodeji', '1718', '2018-07-04', '429', 'paid', ''),
(24, 'Self', 'LID-8875671', 'T141728875048656', '0107742310', 'Akinade Ayodeji', '1289', '2018-08-04', '429', 'paid', ''),
(27, 'Self', 'LID-8875671', 'T359133630358980', '0107742310', 'Akinade Ayodeji', '860', '2018-08-06', '429', 'paid', ''),
(28, 'Self', 'LID-8875671', '', '0107742310', 'Akinade Ayodeji', '431', '2018-08-11', '429', 'pending', ''),
(29, 'Self', 'LID-10662930', 'T677296855946370', '105460083', 'Adebayo Oluwadamilare', '0', '2018-08-17', '1300', 'paid', ''),
(30, 'Self', 'LID-90013493', 'T855962931194886', '105460083', 'Adebayo Oluwadamilare', '0', '2018-08-17', '525', 'paid', '');

-- --------------------------------------------------------

--
-- Table structure for table `payment_schedule`
--

CREATE TABLE `payment_schedule` (
  `id` int(20) NOT NULL,
  `lid` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `term` varchar(200) NOT NULL,
  `day` varchar(200) NOT NULL,
  `schedule` varchar(200) NOT NULL,
  `branchid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_schedule`
--

INSERT INTO `payment_schedule` (`id`, `lid`, `tid`, `term`, `day`, `schedule`, `branchid`) VALUES
(2, 'LID-8875671', '0107742310', '12', '12', 'Monthly', ''),
(4, 'LID-9945699', '0103800879', '1', '1', 'Monthly', ''),
(5, 'LID-10662930', '105460083', '1', '1', 'Monthly', ''),
(6, 'LID-90013493', '105460083', '6', '1', 'Monthly', ''),
(12, 'LID-21599195', '105460083', '1', '20', '15', ''),
(15, 'LID-38339808', '105460083', '1', '20', '20', ''),
(16, 'LID-65582190', '0102460083', '1', '20', '15', ''),
(17, 'LID-88770896', '0101501578', '6', '2', 'Monthly', ''),
(18, 'LID-99662884', '0101501578', '1', '20', '20', 'BR19157'),
(21, 'LID-56686030', '0101501578', '1', '20', '20', 'BR19157');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(20) NOT NULL,
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
  `comment` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `branchid`, `staff_id`, `bizname`, `pay_date`, `basic_pay`, `overtime`, `paid_leave`, `transport_allowance`, `medical_allowance`, `bonus`, `other_allowance`, `gross_amount`, `pension`, `health_insurance`, `unpaid_leave`, `tax_deduction`, `salary_loan`, `total_deduction`, `paid_amount`, `payment_method`, `bank_name`, `acctno`, `adesc`, `comment`) VALUES
(1, 'BR19157', '484', 'International Micro-finance Organization  (IMON)', '2018-05-18', '2500', '150', '0', '0', '0', '50', '0', '2700', '0', '250', '0', '100', '1200', '1550', '1150', 'Transfer', 'Access', '0056164488', 'Paid', 'Balance paid directly to your bank account.'),
(2, 'BR19157', '487', 'International Micro-finance Organization  (IMON)', '2018-05-19', '500', '20', '0', '50', '50', '0', '0', '620', '0', '0', '0', '100', '200', '300', '320', 'Bank Transfer', 'GTBank', '0124357607', 'Balance Paid', 'The Remaining Balance has been fully paid.'),
(3, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pay_schedule`
--

CREATE TABLE `pay_schedule` (
  `id` int(20) NOT NULL,
  `lid` varchar(50) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `schedule` varchar(200) NOT NULL,
  `balance` varchar(200) NOT NULL,
  `payment` varchar(200) NOT NULL,
  `status` varchar(20) NOT NULL COMMENT 'PAID or UNPAID',
  `branchid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pay_schedule`
--

INSERT INTO `pay_schedule` (`id`, `lid`, `get_id`, `tid`, `schedule`, `balance`, `payment`, `status`, `branchid`) VALUES
(74, 'LID-8875671', '4', '0107742310', '2018-06-09', '4721', '429', 'PAID', ''),
(75, 'LID-8875671', '4', '0107742310', '2018-07-09', '4292', '429', 'PAID', ''),
(76, 'LID-8875671', '4', '0107742310', '2018-08-09', '3863', '429', 'PAID', ''),
(77, 'LID-8875671', '4', '0107742310', '2018-09-09', '3434', '429', 'PAID', ''),
(78, 'LID-8875671', '4', '0107742310', '2018-10-09', '3005', '429', 'PAID', ''),
(79, 'LID-8875671', '4', '0107742310', '2018-11-09', '2576', '429', 'PAID', ''),
(80, 'LID-8875671', '4', '0107742310', '2018-12-09', '2147', '429', 'PAID', ''),
(81, 'LID-8875671', '4', '0107742310', '2019-01-09', '1718', '429', 'PAID', ''),
(82, 'LID-8875671', '4', '0107742310', '2019-02-09', '1289', '429', 'PAID', ''),
(83, 'LID-8875671', '4', '0107742310', '2019-03-09', '860', '429', 'PAID', ''),
(84, 'LID-8875671', '4', '0107742310', '2019-04-09', '431', '429', 'UNPAID', ''),
(85, 'LID-8875671', '4', '0107742310', '2019-05-09', '2', '429', 'UNPAID', ''),
(88, 'LID-8875671', '4', '0107742310', '2019-06-09', '0', '2', 'UNPAID', ''),
(100, 'LID-9945699', '6', '0103800879', '2018-09-13', '0', '13000', 'UNPAID', ''),
(101, 'LID-10662930', '7', '105460083', '2018-08-16', '0', '1300', 'PAID', ''),
(102, 'LID-90013493', '8', '105460083', '2018-08-17', '0', '525', 'PAID', ''),
(111, 'LID-38339808', '17', '105460083', '2018-09-17', '0', '12000', 'UNPAID', ''),
(119, 'LID-21599195', '14', '105460083', '2018-09-25', '0', '3600', 'UNPAID', ''),
(120, 'LID-65582190', '18', '0102460083', '2018-09-18', '0', '1200', 'UNPAID', '');

-- --------------------------------------------------------

--
-- Table structure for table `savings_plan`
--

CREATE TABLE `savings_plan` (
  `id` int(20) NOT NULL,
  `plan_code` varchar(50) NOT NULL,
  `plan` varchar(50) NOT NULL,
  `interest` varchar(50) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `pinterval` varchar(50) NOT NULL,
  `effective_duration` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `savings_plan`
--

INSERT INTO `savings_plan` (`id`, `plan_code`, `plan`, `interest`, `amount`, `pinterval`, `effective_duration`) VALUES
(8, 'PLN_f8kwcn0hqn060sn', 'Testing Investment', '20', '100000', 'annually', '6');

-- --------------------------------------------------------

--
-- Table structure for table `savings_subscription`
--

CREATE TABLE `savings_subscription` (
  `id` int(20) NOT NULL,
  `plan_code` varchar(50) NOT NULL,
  `subscription_code` varchar(50) NOT NULL,
  `acn` varchar(50) NOT NULL,
  `date_time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `short_urls`
--

CREATE TABLE `short_urls` (
  `id` int(10) UNSIGNED NOT NULL,
  `long_url` varchar(255) NOT NULL,
  `short_code` varbinary(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `short_urls`
--

INSERT INTO `short_urls` (`id`, `long_url`, `short_code`) VALUES
(3, 'http://127.0.0.1/newloan/application/generate_payslip.php?id=2', 0x6f71756c),
(4, 'http://127.0.0.1/newloan/application/generate_payslip.php?id=3', 0x7335326e);

-- --------------------------------------------------------

--
-- Table structure for table `skey`
--

CREATE TABLE `skey` (
  `id` int(20) NOT NULL,
  `status` varchar(100) NOT NULL COMMENT 'Locked Or Unlocked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skey`
--

INSERT INTO `skey` (`id`, `status`) VALUES
(4, 'Unlocked');

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE `sms` (
  `id` int(11) NOT NULL,
  `sms_gateway` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `api` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms`
--

INSERT INTO `sms` (`id`, `sms_gateway`, `username`, `password`, `api`, `status`) VALUES
(1, 'CriTechSMS', 'username', 'password', 'http://critechglobal.com/mysms/components/com_spc/smsapi.php?', 'NotActivated');

-- --------------------------------------------------------

--
-- Table structure for table `sponsors`
--

CREATE TABLE `sponsors` (
  `id` int(20) NOT NULL,
  `org_image` varchar(200) NOT NULL,
  `org_name` varchar(250) NOT NULL,
  `org_link` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff_module_permission`
--

CREATE TABLE `staff_module_permission` (
  `id` int(20) NOT NULL,
  `staff_tid` varchar(50) NOT NULL,
  `branch_tab` varchar(1) NOT NULL,
  `add_branch` varchar(1) NOT NULL,
  `list_branch` varchar(1) NOT NULL,
  `edit_branch` varchar(1) NOT NULL,
  `del_branch` varchar(1) NOT NULL,
  `customer_tab` varchar(1) NOT NULL,
  `new_customer` varchar(1) NOT NULL,
  `all_customer` varchar(1) NOT NULL,
  `borrower_list` varchar(1) NOT NULL,
  `send_sms` varchar(1) NOT NULL,
  `send_email` varchar(1) NOT NULL,
  `del_customer` varchar(1) NOT NULL,
  `view_account_info` varchar(1) NOT NULL,
  `update_info` varchar(1) NOT NULL,
  `add_to_borrower` varchar(1) NOT NULL,
  `mywallet_tab` varchar(1) NOT NULL,
  `transfer_money` varchar(1) NOT NULL,
  `add_wallet` varchar(1) NOT NULL,
  `reverse_money` varchar(1) NOT NULL,
  `reverse_transfer` varchar(1) NOT NULL,
  `loan_tab` varchar(1) NOT NULL,
  `new_loan` varchar(1) NOT NULL,
  `view_loan` varchar(1) NOT NULL,
  `due_loan` varchar(1) NOT NULL,
  `past_maturity_date` varchar(1) NOT NULL,
  `principal_outstanding` varchar(1) NOT NULL,
  `approve_loan` varchar(1) NOT NULL,
  `loan_details` varchar(1) NOT NULL,
  `del_loan` varchar(1) NOT NULL,
  `print_loan` varchar(1) NOT NULL,
  `export_excel_loanlist` varchar(1) NOT NULL,
  `payment_tab` varchar(1) NOT NULL,
  `new_payment` varchar(1) NOT NULL,
  `list_payment` varchar(1) NOT NULL,
  `del_payment` varchar(1) NOT NULL,
  `print_payment` varchar(1) NOT NULL,
  `export_excel_lpayment` varchar(1) NOT NULL,
  `emp_tab` varchar(1) NOT NULL,
  `new_emp` varchar(1) NOT NULL,
  `list_emp` varchar(1) NOT NULL,
  `edit_emp` varchar(1) NOT NULL,
  `send_empsms` varchar(1) NOT NULL,
  `del_emp` varchar(1) NOT NULL,
  `print_emp` varchar(1) NOT NULL,
  `export_excel_emp` varchar(1) NOT NULL,
  `expense_tab` varchar(1) NOT NULL,
  `add_expense` varchar(1) NOT NULL,
  `view_expense` varchar(1) NOT NULL,
  `del_expense` varchar(1) NOT NULL,
  `edit_expense` varchar(1) NOT NULL,
  `payroll_tab` varchar(1) NOT NULL,
  `add_payroll` varchar(1) NOT NULL,
  `view_payroll` varchar(1) NOT NULL,
  `del_payroll` varchar(1) NOT NULL,
  `edit_payroll` varchar(1) NOT NULL,
  `savings_tab` varchar(1) NOT NULL,
  `deposit_money` varchar(1) NOT NULL,
  `withdraw_money` varchar(1) NOT NULL,
  `all_transaction` varchar(1) NOT NULL,
  `verify_account` varchar(1) NOT NULL,
  `del_transaction` varchar(1) NOT NULL,
  `print_transaction` varchar(1) NOT NULL,
  `export_excel_transaction` varchar(1) NOT NULL,
  `report_tab` varchar(1) NOT NULL,
  `borrower_report` varchar(1) NOT NULL,
  `collection_report` varchar(1) NOT NULL,
  `collector_report` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff_module_permission`
--

INSERT INTO `staff_module_permission` (`id`, `staff_tid`, `branch_tab`, `add_branch`, `list_branch`, `edit_branch`, `del_branch`, `customer_tab`, `new_customer`, `all_customer`, `borrower_list`, `send_sms`, `send_email`, `del_customer`, `view_account_info`, `update_info`, `add_to_borrower`, `mywallet_tab`, `transfer_money`, `add_wallet`, `reverse_money`, `reverse_transfer`, `loan_tab`, `new_loan`, `view_loan`, `due_loan`, `past_maturity_date`, `principal_outstanding`, `approve_loan`, `loan_details`, `del_loan`, `print_loan`, `export_excel_loanlist`, `payment_tab`, `new_payment`, `list_payment`, `del_payment`, `print_payment`, `export_excel_lpayment`, `emp_tab`, `new_emp`, `list_emp`, `edit_emp`, `send_empsms`, `del_emp`, `print_emp`, `export_excel_emp`, `expense_tab`, `add_expense`, `view_expense`, `del_expense`, `edit_expense`, `payroll_tab`, `add_payroll`, `view_payroll`, `del_payroll`, `edit_payroll`, `savings_tab`, `deposit_money`, `withdraw_money`, `all_transaction`, `verify_account`, `del_transaction`, `print_transaction`, `export_excel_transaction`, `report_tab`, `borrower_report`, `collection_report`, `collector_report`) VALUES
(2, 'Loan=176520691', '0', '0', '0', '0', '0', '1', '1', '1', '0', '1', '1', '0', '0', '0', '0', '1', '1', '0', '0', '0', '1', '1', '1', '0', '0', '0', '0', '1', '0', '0', '0', '1', '1', '1', '0', '0', '0', '1', '1', '1', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '1', '0', '1', '0', '0', '0', '0', '1', '1', '1', '0'),
(3, 'Loan=172643433', '0', '0', '0', '0', '0', '1', '1', '1', '1', '1', '1', '0', '1', '0', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '0', '1', '1', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '1', '1', '0', '0', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `systemset`
--

CREATE TABLE `systemset` (
  `sysid` int(11) NOT NULL,
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
  `paypal_email` varchar(200) NOT NULL,
  `map` text NOT NULL,
  `stamp` varchar(350) NOT NULL,
  `timezone` text NOT NULL,
  `sms_charges` varchar(200) NOT NULL,
  `withdrawal_fee` varchar(20) NOT NULL,
  `theme_color` varchar(20) NOT NULL,
  `min_interest_rate` varchar(50) NOT NULL,
  `max_interest_rate` varchar(50) NOT NULL,
  `secret_key` varchar(200) NOT NULL,
  `public_key` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `systemset`
--

INSERT INTO `systemset` (`sysid`, `title`, `name`, `descr`, `keyw`, `footer`, `abb`, `fax`, `bcountry`, `currency`, `website`, `mobile`, `dhotline`, `image`, `address`, `email`, `paypal_email`, `map`, `stamp`, `timezone`, `sms_charges`, `withdrawal_fee`, `theme_color`, `min_interest_rate`, `max_interest_rate`, `secret_key`, `public_key`) VALUES
(1, 'Advance Loan / Lending Management System with SMS Notification & Saving System for Micro Finance Bank', 'International Micro-finance Organization  (IMON)', 'IMON - Nonprofit, Crowdfunding, Charity & Micro-finance Organization', 'charity,crowdfunding,nonprofit,orphan,Poor,funding,fundrising,ngo,children', 'All rights reserved. 2017 (c)', 'IMON', '23459', 'United States', 'NGN', 'https://www.critechglobal.com', '1-888-717-IMON', '1-888-717-IMON', '../img/agruni.jpg', '1101 Connecticut Ave NW Suite 405 Washington DC 20036								', 'supports@critechglobal.com', 'critech.getresponse@gmail.com', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3104.872333485419!2d-77.0418138850062!3d38.90403467956958!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89b7b7b9283523a9%3A0x9dea77ed2928191a!2s1101+Connecticut+Ave+NW+%23405%2C+Washington%2C+DC+20036%2C+USA!5e0!3m2!1sen!2sng!4v1524956810185\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>			', 'stamp.jpg', '-12', '100', '10', 'blue', '5', '25', 'sk_test_5ea19afa962644498d50d73af1d5c5b4435e481e', 'pk_test_790aff0570a98aecaa4573db4d59a1755992f36b');

-- --------------------------------------------------------

--
-- Table structure for table `testimonies`
--

CREATE TABLE `testimonies` (
  `id` int(20) NOT NULL,
  `b_id` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(20) NOT NULL,
  `txid` varchar(200) NOT NULL,
  `t_type` varchar(200) NOT NULL COMMENT 'Deposit OR Withdraw',
  `acctno` varchar(200) NOT NULL,
  `transfer_to` varchar(200) NOT NULL,
  `fn` varchar(200) NOT NULL,
  `ln` varchar(200) NOT NULL,
  `email` varchar(300) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `posted_by` varchar(50) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `branchid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `txid`, `t_type`, `acctno`, `transfer_to`, `fn`, `ln`, `email`, `phone`, `amount`, `posted_by`, `date_time`, `branchid`) VALUES
(1, 'TXID-55937683', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '5', '', '2018-04-17 10:16:25', ''),
(2, 'TXID-96835816', 'Withdraw', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '3', '', '2018-04-17 10:16:37', ''),
(3, 'TXID-79381958', 'Withdraw', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '2', '', '2018-04-17 10:16:45', ''),
(4, 'TXID-42491394', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '10', '', '2018-04-17 10:16:53', ''),
(5, 'TXID-74638672', 'Withdraw', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '5', '', '2018-04-17 10:17:00', ''),
(6, 'TXID-15410400', 'Deposit', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '08057102359', '50000', '', '2018-04-17 10:17:08', ''),
(7, 'TXID-12078735', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '150000', '', '2018-04-17 10:17:15', ''),
(8, 'TXID-38809814', 'Withdraw', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '2500', '', '2018-04-17 10:17:22', ''),
(9, 'TXID-51565247', 'Withdraw', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '08057102359', '1800', '', '2018-04-17 10:17:31', ''),
(14, 'TXID-68376099', 'Withdraw-Charges', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '08057102359', '10', '', '2018-04-17 10:17:38', ''),
(15, 'TXID-68376099', 'Withdraw', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '08057102359', '1150', '', '2018-04-17 10:17:51', ''),
(16, 'TXID-82851197', 'Withdraw', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '1450', '', '2018-04-17 10:17:59', ''),
(17, 'TXID-86981385', 'Transfer', '0107742310', '0102460083', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '08057102359', '1250', '', '2018-04-17 10:05:41', ''),
(18, 'TXID-86981385', 'Transfer-Received', '0102460083', '0107742310', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '1250', '', '2018-04-17 10:24:07', ''),
(19, 'TXID-21807556', 'Transfer', '0102460083', '0107742310', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '1000', '', '2018-04-17 09:48:23', ''),
(20, 'TXID-21807556', 'Transfer-Received', '0107742310', '0102460083', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '08057102359', '1000', '', '2018-04-17 09:48:23', ''),
(21, 'TXID-43756470', 'Transfer', '0107742310', '0102460083', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '08057102359', '50.55', '', '2018-04-17 10:11:43', ''),
(22, 'TXID-43756470', 'Transfer-Received', '0102460083', '0107742310', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08057102359', '50.55', '', '2018-04-17 10:11:43', ''),
(23, 'TXID-25697980', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '250', '', '2018-05-28 08:46:04', ''),
(24, 'TXID-37668929', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '200', '', '2018-05-28 08:52:29', ''),
(25, 'TXID-72193756', 'Deposit', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '09057102359', '150', '', '2018-05-28 08:56:27', ''),
(26, 'TXID-12181784', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '100', '', '2018-05-28 08:56:42', ''),
(27, 'TXID-47828462', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '120', '', '2018-05-28 09:02:31', ''),
(28, 'TXID-83105027', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '50', '', '2018-05-28 09:10:07', ''),
(29, 'TXID-49038192', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '450', '', '2018-05-28 09:12:29', ''),
(30, 'TXID-92872538', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '135', '', '2018-05-28 09:24:44', ''),
(31, 'TXID-14392615', 'Withdraw', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '09057102359', '1000', '', '2018-07-27 23:23:39', ''),
(32, 'TXID-66558974', 'Deposit', '0101501578', '----', 'demo', 'demo', 'acpalace2016@gmail.com', '08101750845', '1500', '', '2018-07-27 23:35:38', ''),
(33, 'TXID-10447624', 'Withdraw-Charges', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '09057102359', '10', '', '2018-08-06 16:38:57', ''),
(34, 'TXID-10447624', 'Withdraw', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '09057102359', '20350', '', '2018-08-06 16:38:57', ''),
(35, 'TXID-78607588', 'Withdraw-Charges', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '09057102359', '10', '', '2018-08-06 16:46:04', ''),
(36, 'TXID-78607588', 'Withdraw', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '09057102359', '2000', '', '2018-08-06 16:46:04', ''),
(37, 'TXID-53422714', 'Withdraw-Charges', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '09057102359', '10', '', '2018-08-06 17:02:25', ''),
(38, 'TXID-53422714', 'Withdraw', '0107742310', '----', 'Akinade', 'Ayodeji', 'critech.getsolution@yahoo.com', '09057102359', '1111', '', '2018-08-06 17:02:25', ''),
(39, 'TXID-94877096', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '2323', '', '2018-08-06 17:29:53', ''),
(40, 'TXID-33535925', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '1010', '', '2018-08-06 17:39:47', ''),
(41, 'TXID-52450861', 'Deposit', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '100', '', '2018-08-06 17:41:11', ''),
(42, 'TXID-18495107', 'Withdraw-Charges', '0101501578', '----', 'demo', 'demo', 'acpalace2016@gmail.com', '08101750845', '10', '', '2018-08-06 18:12:24', 'BR19157'),
(43, 'TXID-18495107', 'Withdraw', '0101501578', '----', 'demo', 'demo', 'acpalace2016@gmail.com', '08101750845', '250', '', '2018-08-06 18:12:24', 'BR19157'),
(44, 'TXID-33824232', 'Withdraw', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '10000', '', '2018-08-10 15:56:02', ''),
(45, 'TXID-2510630', 'Withdraw-Charges', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '10', '', '2018-08-10 16:00:28', ''),
(46, 'TXID-2510630', 'Withdraw', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '1001', '', '2018-08-10 16:00:28', ''),
(47, 'TXID-2531985', 'Withdraw-Charges', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '10', '', '2018-08-10 16:05:02', ''),
(48, 'TXID-2531985', 'Withdraw', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '100', '', '2018-08-10 16:05:02', ''),
(49, 'TXID-30859046', 'Withdraw-Charges', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '10', '', '2018-08-10 16:48:32', ''),
(50, 'TXID-30859046', 'Withdraw', '0102460083', '----', 'Olatunde', 'Timilehin', 'timilehingbemi@gmail.com', '08101750845', '200', '', '2018-08-10 16:48:32', ''),
(52, 'T587839433668891', 'Deposit', '105460083', '----', 'Adebayo', 'Oluwadamilare', 'timilehingbemi@gmail.com', '8123234876', '500', '', '2018-08-17 11:51:56', '');

-- --------------------------------------------------------

--
-- Table structure for table `twallet`
--

CREATE TABLE `twallet` (
  `id` int(20) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `Total` varchar(200) NOT NULL,
  `branchid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `twallet`
--

INSERT INTO `twallet` (`id`, `tid`, `Total`, `branchid`) VALUES
(1, 'Loan=21319580', '350000', ''),
(3, 'Loan=176520691', '3450', 'BR44999');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
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
  `utype` varchar(200) NOT NULL COMMENT 'Registered OR Unregistered'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `name`, `email`, `phone`, `addr1`, `addr2`, `city`, `state`, `zip`, `country`, `comment`, `username`, `password`, `id`, `image`, `role`, `branchid`, `utype`) VALUES
(482, 'Akin James', 'admin@critechglobal.com', '08101750845', 'address1', 'address2', 'city', 'state', 'zip', 'US', 'comment', 'admin', 'YWRtaW4=', 'Loan=21319580', 'img/apple.jpg', 'super-admin', '', 'Registered'),
(483, 'AKINADE AYODEJI', 'critech.getresponse@gmail.com', '08123234876', 'demo', 'demo', 'demop', 'demo', '12345', 'US', '', 'staff1', 'c3RhZmYx', 'Loan=172643433', 'img/EMP.png', 'Staff', 'BR19157', 'Registered'),
(484, 'Akinwale Sunday', 'sanjeevdotasara@gmail.com', '08101750845', 'test', 'test', 'test', 'test', '18373', 'Nigeria', '', 'staff2', 'c3RhZmYy', 'Loan=176520691', 'img/IMG-20170906-WA0008.jpg', 'Staff', 'BR44999', 'Registered'),
(487, 'Adeolu Johnson', 'adjohnson@gmail.com', '08123234876', 'testing', 'testing again', 'Lagos', 'Lagos', '23401', 'Nigeria', '', '', '', 'Loan=214366760', '', '', 'BR19157', 'Unregistered');

-- --------------------------------------------------------

--
-- Table structure for table `video_advert`
--

CREATE TABLE `video_advert` (
  `id` int(20) NOT NULL,
  `campaign_title` varchar(200) NOT NULL,
  `campaign_url` varchar(500) NOT NULL,
  `campaign_desc` text NOT NULL,
  `status` varchar(20) NOT NULL COMMENT 'Normal or Featured'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

CREATE TABLE `volunteer` (
  `id` int(20) NOT NULL,
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
  `v_type` varchar(50) NOT NULL COMMENT 'Normal or Featured'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vpost`
--

CREATE TABLE `vpost` (
  `id` int(20) NOT NULL,
  `image` varchar(200) NOT NULL,
  `headings` varchar(200) NOT NULL,
  `motivation_words` text NOT NULL,
  `vlink` varchar(200) NOT NULL,
  `action_btn` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL COMMENT 'Show or Hide'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vpost`
--

INSERT INTO `vpost` (`id`, `image`, `headings`, `motivation_words`, `vlink`, `action_btn`, `status`) VALUES
(1, '../img/volunteer-make-a-difference.png', 'Become a Proud Volunteer', 'No one has ever become poor by giving. Join us today and make a difference today as there is no better reward than putting smile on a needy family.', 'campaign.php', 'GET INVOLVED NOW!', 'Show');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_note`
--

CREATE TABLE `withdrawal_note` (
  `id` int(20) NOT NULL,
  `mytid` varchar(50) NOT NULL,
  `b_id` varchar(50) NOT NULL,
  `c_id` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `wnote` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_request`
--

CREATE TABLE `withdrawal_request` (
  `id` int(20) NOT NULL,
  `wid` varchar(100) NOT NULL,
  `b_id` varchar(50) NOT NULL,
  `c_id` varchar(50) NOT NULL,
  `w_details` text NOT NULL,
  `w_amount` varchar(200) NOT NULL,
  `interest_rate` varchar(50) NOT NULL,
  `amt_withdrawable` varchar(50) NOT NULL,
  `w_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `wstatus` varchar(100) NOT NULL COMMENT 'Approved or Declined or Pending',
  `branchid` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aboutus`
--
ALTER TABLE `aboutus`
  ADD PRIMARY KEY (`abid`);

--
-- Indexes for table `activate_member`
--
ALTER TABLE `activate_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `additional_fees`
--
ALTER TABLE `additional_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `authorized_card`
--
ALTER TABLE `authorized_card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `backup`
--
ALTER TABLE `backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`banaid`);

--
-- Indexes for table `battachment`
--
ALTER TABLE `battachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bcountries`
--
ALTER TABLE `bcountries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowers`
--
ALTER TABLE `borrowers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaign_cat`
--
ALTER TABLE `campaign_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaign_lendpay_history`
--
ALTER TABLE `campaign_lendpay_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaign_pay_history`
--
ALTER TABLE `campaign_pay_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `causes`
--
ALTER TABLE `causes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `causes_note`
--
ALTER TABLE `causes_note`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collateral`
--
ALTER TABLE `collateral`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deptors`
--
ALTER TABLE `deptors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_permission`
--
ALTER TABLE `emp_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_role`
--
ALTER TABLE `emp_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `etemplates`
--
ALTER TABLE `etemplates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_document`
--
ALTER TABLE `expense_document`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exptype`
--
ALTER TABLE `exptype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fin_info`
--
ALTER TABLE `fin_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer`
--
ALTER TABLE `footer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hiw`
--
ALTER TABLE `hiw`
  ADD PRIMARY KEY (`hid`);

--
-- Indexes for table `interest_calculator`
--
ALTER TABLE `interest_calculator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_info`
--
ALTER TABLE `loan_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_product`
--
ALTER TABLE `loan_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mywallet`
--
ALTER TABLE `mywallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_schedule`
--
ALTER TABLE `payment_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pay_schedule`
--
ALTER TABLE `pay_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `savings_plan`
--
ALTER TABLE `savings_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `savings_subscription`
--
ALTER TABLE `savings_subscription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `short_urls`
--
ALTER TABLE `short_urls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `short_code` (`short_code`);

--
-- Indexes for table `skey`
--
ALTER TABLE `skey`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sponsors`
--
ALTER TABLE `sponsors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_module_permission`
--
ALTER TABLE `staff_module_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `systemset`
--
ALTER TABLE `systemset`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `testimonies`
--
ALTER TABLE `testimonies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `twallet`
--
ALTER TABLE `twallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `video_advert`
--
ALTER TABLE `video_advert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vpost`
--
ALTER TABLE `vpost`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawal_note`
--
ALTER TABLE `withdrawal_note`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawal_request`
--
ALTER TABLE `withdrawal_request`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aboutus`
--
ALTER TABLE `aboutus`
  MODIFY `abid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activate_member`
--
ALTER TABLE `activate_member`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `additional_fees`
--
ALTER TABLE `additional_fees`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attachment`
--
ALTER TABLE `attachment`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `authorized_card`
--
ALTER TABLE `authorized_card`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `backup`
--
ALTER TABLE `backup`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `banaid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `battachment`
--
ALTER TABLE `battachment`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bcountries`
--
ALTER TABLE `bcountries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `borrowers`
--
ALTER TABLE `borrowers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=384;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `campaign_cat`
--
ALTER TABLE `campaign_cat`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `campaign_lendpay_history`
--
ALTER TABLE `campaign_lendpay_history`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaign_pay_history`
--
ALTER TABLE `campaign_pay_history`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `causes`
--
ALTER TABLE `causes`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `causes_note`
--
ALTER TABLE `causes_note`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `collateral`
--
ALTER TABLE `collateral`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deptors`
--
ALTER TABLE `deptors`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `emp_permission`
--
ALTER TABLE `emp_permission`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `emp_role`
--
ALTER TABLE `emp_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `etemplates`
--
ALTER TABLE `etemplates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `expense_document`
--
ALTER TABLE `expense_document`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `exptype`
--
ALTER TABLE `exptype`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fin_info`
--
ALTER TABLE `fin_info`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `footer`
--
ALTER TABLE `footer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hiw`
--
ALTER TABLE `hiw`
  MODIFY `hid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `interest_calculator`
--
ALTER TABLE `interest_calculator`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `loan_info`
--
ALTER TABLE `loan_info`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `loan_product`
--
ALTER TABLE `loan_product`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mywallet`
--
ALTER TABLE `mywallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `payment_schedule`
--
ALTER TABLE `payment_schedule`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pay_schedule`
--
ALTER TABLE `pay_schedule`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `savings_plan`
--
ALTER TABLE `savings_plan`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `savings_subscription`
--
ALTER TABLE `savings_subscription`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `short_urls`
--
ALTER TABLE `short_urls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `skey`
--
ALTER TABLE `skey`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sponsors`
--
ALTER TABLE `sponsors`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_module_permission`
--
ALTER TABLE `staff_module_permission`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `systemset`
--
ALTER TABLE `systemset`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonies`
--
ALTER TABLE `testimonies`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `twallet`
--
ALTER TABLE `twallet`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=488;

--
-- AUTO_INCREMENT for table `video_advert`
--
ALTER TABLE `video_advert`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `volunteer`
--
ALTER TABLE `volunteer`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vpost`
--
ALTER TABLE `vpost`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `withdrawal_note`
--
ALTER TABLE `withdrawal_note`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawal_request`
--
ALTER TABLE `withdrawal_request`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;