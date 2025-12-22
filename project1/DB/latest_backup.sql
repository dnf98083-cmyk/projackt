DROP TABLE IF EXISTS cart;

CREATE TABLE `cart` (
  `cart_id` char(14) NOT NULL,
  `content_code` varchar(50) DEFAULT NULL,
  `content_options` varchar(100) DEFAULT NULL,
  `content_amount` int(100) NOT NULL DEFAULT 1,
  `user_id` char(15) NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO cart VALUES("20221127104017","221119-005","#76a2f9 / free","1","hyun");
INSERT INTO cart VALUES("20221128052924","221124-009","#45d3ca / free","1","ga");
INSERT INTO cart VALUES("20221208044838","221119-002","#257a1f / free","2","song");
INSERT INTO cart VALUES("20221208045922","221124-009","#781212 / free","2","join");
INSERT INTO cart VALUES("20251120053122","221208-001","oooo","1","rlatngla");
INSERT INTO cart VALUES("20251211024527","MK-20251126-0242","오골계삼계탕","2","kong");
INSERT INTO cart VALUES("20251211025219","MK-20251204-0011","이현곤표 소불고기","3","kong");



DROP TABLE IF EXISTS contents;

CREATE TABLE `contents` (
  `content_code` varchar(32) NOT NULL,
  `content_img` varchar(500) NOT NULL,
  `deliv_today` char(1) NOT NULL,
  `content_name` varchar(50) NOT NULL,
  `discount_rate` decimal(3,0) NOT NULL DEFAULT 0,
  `content_cost` int(11) NOT NULL,
  `content_price` int(11) NOT NULL,
  `content_color1` varchar(30) DEFAULT NULL,
  `content_color2` varchar(30) DEFAULT NULL,
  `content_color3` varchar(30) DEFAULT NULL,
  `content_color4` varchar(30) DEFAULT NULL,
  `category_large` varchar(50) DEFAULT NULL,
  `category_small` varchar(50) DEFAULT NULL,
  `content_img1` varchar(500) DEFAULT NULL,
  `content_img2` varchar(500) DEFAULT NULL,
  `content_img3` varchar(500) DEFAULT NULL,
  `content_img4` varchar(500) DEFAULT NULL,
  `content_sales` int(11) DEFAULT 0,
  `content_content` text DEFAULT NULL,
  `registrant_id` varchar(50) DEFAULT NULL,
  `regist_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`content_code`),
  KEY `idx_category_large` (`category_large`),
  KEY `idx_content_price` (`content_price`),
  KEY `idx_content_sales` (`content_sales`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO contents VALUES("MK-20251126-0001","img/organized_ascii/health/MK-20251126-0001.png","N","그릴드 닭가슴살 포케","0","4700","7900","","","","","건강식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0002","img/organized_ascii/health/MK-20251126-0002.png","N","누룽지 삼계탕","0","7000","11000","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0003","img/organized_ascii/health/MK-20251126-0003.png","N","능이 오리백숙","29","7200","11900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0004","img/organized_ascii/health/MK-20251126-0004.png","N","닭가슴살 도시락","0","4400","6900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0005","img/organized_ascii/health/MK-20251126-0005.png","N","닭가슴살","21","3900","5900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0006","img/organized_ascii/health/MK-20251126-0006.png","N","목살 스테이크 샐러드","6","8100","12900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0007","img/organized_ascii/health/MK-20251126-0007.png","N","보양식 해신탕","0","5200","7900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0008","img/organized_ascii/health/MK-20251126-0008.png","N","샐러드 마녀 비프포케 샐러드","0","7800","12000","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0009","img/organized_ascii/health/MK-20251126-0009.png","N","세끼판다 단호박 샐러드","21","4800","8000","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0010","img/organized_ascii/health/MK-20251126-0010.png","N","쉐이크 비프 포케 샐러드","0","6600","10900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0011","img/organized_ascii/health/MK-20251126-0011.png","N","쉐이크 쉬림프 포케 샐러드","9","5500","7900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0012","img/organized_ascii/health/MK-20251126-0012.png","N","오븐 닭가슴살","0","7900","11900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0013","img/organized_ascii/health/MK-20251126-0013.png","N","월남쌈","0","5500","8000","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0014","img/organized_ascii/health/MK-20251126-0014.png","N","전복죽","0","6900","9900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0015","img/organized_ascii/health/MK-20251126-0015.png","N","추어탕","0","9100","13900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0016","img/organized_ascii/other/MK-20251126-0016.png","N","건어물 5종세트","0","5500","7900","","","","","기타","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0017","img/organized_ascii/other/MK-20251126-0017.png","N","슈크림 붕어빵","0","7400","10900","","","","","기타","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0018","img/organized_ascii/western/MK-20251126-0018.png","N","가리비 바질파스타","10","20200","28900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0019","img/organized_ascii/western/MK-20251126-0019.png","N","갈릭비프스테이크","0","9400","13900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0020","img/organized_ascii/western/MK-20251126-0020.png","N","감바스","0","18500","29000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0021","img/organized_ascii/western/MK-20251126-0021.png","N","고르곤졸라피자","23","6600","10900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0022","img/organized_ascii/western/MK-20251126-0022.png","N","매운 크림 리조또","0","7700","11900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0023","img/organized_ascii/western/MK-20251126-0023.png","N","매운 크림 파스타","0","15600","24000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0024","img/organized_ascii/western/MK-20251126-0024.png","N","묵은지 들기름 파스타","0","10500","15000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0025","img/organized_ascii/western/MK-20251126-0025.png","N","베이컨토마토파스타","0","12900","19900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0026","img/organized_ascii/western/MK-20251126-0026.png","N","봉골레 파스타","0","8500","13000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0027","img/organized_ascii/western/MK-20251126-0027.png","N","빕스 부채살 찹스테이크","25","12200","18900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0028","img/organized_ascii/western/MK-20251126-0028.png","N","빕스 클래식 스테이크","0","19300","28900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0029","img/organized_ascii/western/MK-20251126-0029.png","N","새우명란오일파스타","0","16000","22900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0030","img/organized_ascii/western/MK-20251126-0030.png","N","새우봉골레 파스타","0","19900","28900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0031","img/organized_ascii/western/MK-20251126-0031.png","N","생크림 새우 리조또","0","6800","9900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0032","img/organized_ascii/western/MK-20251126-0032.png","N","생크림 새우 파스타","0","7500","10900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0033","img/organized_ascii/western/MK-20251126-0033.png","N","알렌 뼈등심스테이크&페퍼콘소스","0","10300","16900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0034","img/organized_ascii/western/MK-20251126-0034.png","N","알렌 스피니치페스토 에그생면파스타","0","12400","18000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0035","img/organized_ascii/western/MK-20251126-0035.png","N","알렌 아라비아따 에그생면파스타","0","12500","20900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0036","img/organized_ascii/western/MK-20251126-0036.png","N","알리오 올리오 파스타","0","11500","18000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0037","img/organized_ascii/western/MK-20251126-0037.png","N","차돌박이 마늘쫑 오일파스타","0","11200","17000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0038","img/organized_ascii/western/MK-20251126-0038.png","N","찹스테이크","0","17900","26000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0039","img/organized_ascii/western/MK-20251126-0039.png","N","트러플크림 뇨끼","0","20300","29000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0040","img/organized_ascii/western/MK-20251126-0040.png","N","푸타네스카 파스타","0","12100","17900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0041","img/organized_ascii/western/MK-20251126-0041.png","N","해산물 토마토 파스타","0","12600","18000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0042","img/organized_ascii/western/MK-20251126-0042.png","N","화이트 라구 파스타","22","12100","19000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0043","img/organized_ascii/japanese/MK-20251126-0043.png","N","규동 덮밥","0","8000","12000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0044","img/organized_ascii/japanese/MK-20251126-0044.png","N","김재현 홈마카세 스시 밀키트","17","12800","21000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0045","img/organized_ascii/japanese/MK-20251126-0045.png","N","돈코츠 라멘","0","20700","34000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0046","img/organized_ascii/japanese/MK-20251126-0046.png","N","등심돈까스","0","22300","34900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0047","img/organized_ascii/japanese/MK-20251126-0047.png","N","명란구이","0","9100","13900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0048","img/organized_ascii/japanese/MK-20251126-0048.png","N","명란크림소바","0","16700","23900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0049","img/organized_ascii/japanese/MK-20251126-0049.png","N","모츠나베","0","16600","26000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0050","img/organized_ascii/japanese/MK-20251126-0050.png","N","문어가라아게","0","5900","9900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0051","img/organized_ascii/japanese/MK-20251126-0051.png","N","밀푀유나베","0","14900","24900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0052","img/organized_ascii/japanese/MK-20251126-0052.png","N","바질 고르곤졸라 뇨끼","0","6800","9900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0053","img/organized_ascii/japanese/MK-20251126-0053.png","N","베이컨 야끼소바","0","6200","9900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0054","img/organized_ascii/japanese/MK-20251126-0054.png","N","사누끼우동면","0","13500","20900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0055","img/organized_ascii/japanese/MK-20251126-0055.png","N","삿포로생라멘면","0","16400","27000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0056","img/organized_ascii/japanese/MK-20251126-0056.png","N","소고기 규동 밀키트","0","16500","25900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0057","img/organized_ascii/japanese/MK-20251126-0057.png","N","소고기 스키야키","0","19400","30900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0058","img/organized_ascii/japanese/MK-20251126-0058.png","N","소고기 야끼소바","0","11000","16000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0059","img/organized_ascii/japanese/MK-20251126-0059.png","N","야끼찌꾸와","0","20400","31000","","","","","일식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0060","img/organized_ascii/japanese/MK-20251126-0060.png","N","야키토리 수제꼬치","0","16400","24900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0061","img/organized_ascii/japanese/MK-20251126-0061.png","N","양념장어덮밥","0","18500","29000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0062","img/organized_ascii/japanese/MK-20251126-0062.png","N","오뎅꼬치","19","17000","25000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0063","img/organized_ascii/japanese/MK-20251126-0063.png","N","장어구이","0","5900","8900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0064","img/organized_ascii/japanese/MK-20251126-0064.png","N","치킨 가라아게","0","6100","8900","","","","","일식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0065","img/organized_ascii/japanese/MK-20251126-0065.png","N","타코야끼","0","20300","30900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0066","img/organized_ascii/japanese/MK-20251126-0066.png","N","타코와사비","0","12800","21000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0067","img/organized_ascii/japanese/MK-20251126-0067.png","N","트러플 크림 뇨끼","22","20000","29000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0068","img/organized_ascii/japanese/MK-20251126-0068.png","N","해물 오코노미야끼","0","7800","12000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0069","img/organized_ascii/japanese/MK-20251126-0069.png","N","헤믈 나가사키 짬뽕","0","13300","19900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0070","img/organized_ascii/japanese/MK-20251126-0070.png","N","호쿠쇼쿠 타코와사비","0","8000","11900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0071","img/organized_ascii/japanese/MK-20251126-0071.png","N","홍게 오뎅탕","0","22700","33900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0072","img/organized_ascii/chinese/MK-20251126-0072.png","N","고추잡채와 꽃빵","20","10400","16900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0073","img/organized_ascii/chinese/MK-20251126-0073.png","N","교동짬뽕","17","6000","8900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0074","img/organized_ascii/chinese/MK-20251126-0074.png","N","깐풍기","0","3700","6000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0075","img/organized_ascii/chinese/MK-20251126-0075.png","N","꿔바로우","0","6600","11000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0076","img/organized_ascii/chinese/MK-20251126-0076.png","N","동파육","0","8500","14000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0077","img/organized_ascii/chinese/MK-20251126-0077.png","N","동파육만두","0","12500","20900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0078","img/organized_ascii/chinese/MK-20251126-0078.png","N","마라 바지락 볶음","23","7800","12900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0079","img/organized_ascii/chinese/MK-20251126-0079.png","N","마라샹궈","0","4200","6000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0080","img/organized_ascii/chinese/MK-20251126-0080.png","N","마라탕","0","12600","21000","","","","","중식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0081","img/organized_ascii/chinese/MK-20251126-0081.png","N","마파두부","0","11500","18000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0082","img/organized_ascii/chinese/MK-20251126-0082.png","N","삼겹 동파육","0","6000","8900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0083","img/organized_ascii/chinese/MK-20251126-0083.png","N","양고기","0","7900","13000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0084","img/organized_ascii/chinese/MK-20251126-0084.png","N","쟁반짜장","0","11300","16900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0085","img/organized_ascii/chinese/MK-20251126-0085.png","N","중화풍 양장피","0","4600","7000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0086","img/organized_ascii/chinese/MK-20251126-0086.png","N","중화풍 팔보채","22","4500","7000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0087","img/organized_ascii/chinese/MK-20251126-0087.png","N","칠리새우","0","9800","15900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0088","img/organized_ascii/chinese/MK-20251126-0088.png","N","티앤미미 동파육","0","11700","19000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0089","img/organized_ascii/chinese/MK-20251126-0089.png","N","티앤미미 비빔새우","21","4700","6900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0090","img/organized_ascii/chinese/MK-20251126-0090.png","N","티앤미미 새우완탕면","0","6300","9900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0091","img/organized_ascii/chinese/MK-20251126-0091.png","N","티앤미미 우삼겹차우면","0","13500","20900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0092","img/organized_ascii/chinese/MK-20251126-0092.png","N","향라새우","0","10300","15900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0093","img/organized_ascii/korean/MK-20251126-0093.png","N","감자옹심이 손칼국수","0","15900","24900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0094","img/organized_ascii/korean/MK-20251126-0094.png","N","감자탕","0","14200","20900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0095","img/organized_ascii/korean/MK-20251126-0095.png","N","고추장 돼지불백","0","8700","13000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0096","img/organized_ascii/korean/MK-20251126-0096.png","N","곱창전골","0","9600","16000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0097","img/organized_ascii/korean/MK-20251126-0097.png","N","광양식 소불고기볶음밥","0","9500","15900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0098","img/organized_ascii/korean/MK-20251126-0098.png","N","김치 콩나물 국밥","9","8200","12900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0099","img/organized_ascii/korean/MK-20251126-0099.png","N","껍데기 소금구이","0","12800","20000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0100","img/organized_ascii/korean/MK-20251126-0100.png","N","꽃게탕 해물 된장찌개","0","5600","8000","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0101","img/organized_ascii/korean/MK-20251126-0101.png","N","냉모밀소바","0","12900","19900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0102","img/organized_ascii/korean/MK-20251126-0102.png","N","냉채족발","0","7500","12000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0103","img/organized_ascii/korean/MK-20251126-0103.png","N","능이삼계탕","0","8300","13000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0104","img/organized_ascii/korean/MK-20251126-0104.png","N","닭내장탕","0","15600","24900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0105","img/organized_ascii/korean/MK-20251126-0105.png","N","닭발","0","10300","17000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0106","img/organized_ascii/korean/MK-20251126-0106.png","N","닭볶음탕","0","6100","8900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0107","img/organized_ascii/korean/MK-20251126-0107.png","N","대창김치찜","0","16400","26900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0108","img/organized_ascii/korean/MK-20251126-0108.png","N","도가니탕","0","9500","14000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0109","img/organized_ascii/korean/MK-20251126-0109.png","N","돌게&딱새우 된장찌개","0","7000","10900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0110","img/organized_ascii/korean/MK-20251126-0110.png","N","동태탕","0","18300","27000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0111","img/organized_ascii/korean/MK-20251126-0111.png","N","돼지 꼬리족발","0","10000","15000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0112","img/organized_ascii/korean/MK-20251126-0112.png","N","돼지고기 김치찜","0","17400","26000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0113","img/organized_ascii/korean/MK-20251126-0113.png","N","돼지국밥","0","9800","15900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0114","img/organized_ascii/korean/MK-20251126-0114.png","N","들기름 막국수","0","9600","15000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0115","img/organized_ascii/korean/MK-20251126-0115.png","N","매콤 곱창볶음","23","13300","19900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0116","img/organized_ascii/korean/MK-20251126-0116.png","N","먹태구이","0","13500","19900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0117","img/organized_ascii/korean/MK-20251126-0117.png","N","멸치잔치국수","0","13100","19000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0118","img/organized_ascii/korean/MK-20251126-0118.png","N","물냉면","0","17700","26900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0119","img/organized_ascii/korean/MK-20251126-0119.png","N","민물 장어탕","11","5900","8900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0120","img/organized_ascii/korean/MK-20251126-0120.png","N","바지락 황태 칼국수","0","9000","15000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0121","img/organized_ascii/korean/MK-20251126-0121.png","N","바지락칼국수","8","7300","11900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0122","img/organized_ascii/korean/MK-20251126-0122.png","N","부대찌개","0","11700","18900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0123","img/organized_ascii/korean/MK-20251126-0123.png","N","부산약콩밀면","0","14800","23900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0124","img/organized_ascii/korean/MK-20251126-0124.png","N","불고기전골","0","9900","15000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0125","img/organized_ascii/korean/MK-20251126-0125.png","N","비빔 칼국수","0","8400","13000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0126","img/organized_ascii/korean/MK-20251126-0126.png","N","비빔낙지젓갈","18","10400","16000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0127","img/organized_ascii/korean/MK-20251126-0127.png","N","비빔냉면과 왕만두","0","7100","11000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0128","img/organized_ascii/korean/MK-20251126-0128.png","N","뼈해장국","0","5600","8000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0129","img/organized_ascii/korean/MK-20251126-0129.png","N","사골시래기육개장","0","9500","14900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0130","img/organized_ascii/korean/MK-20251126-0130.png","N","생아귀탕","0","6600","10000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0131","img/organized_ascii/korean/MK-20251126-0131.png","N","선지해장국","0","8500","13000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0132","img/organized_ascii/korean/MK-20251126-0132.png","N","설렁탕","0","16000","25000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0133","img/organized_ascii/korean/MK-20251126-0133.png","N","소갈비탕","5","16700","23900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0134","img/organized_ascii/korean/MK-20251126-0134.png","N","소고기 국밥","0","13600","22000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0135","img/organized_ascii/korean/MK-20251126-0135.png","N","소고기 된장전골","0","16800","25900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0136","img/organized_ascii/korean/MK-20251126-0136.png","N","소고기 샤브샤브","0","6100","10000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0137","img/organized_ascii/korean/MK-20251126-0137.png","N","소고기미역국","0","15800","23000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0138","img/organized_ascii/korean/MK-20251126-0138.png","N","소곱창","0","14800","23900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0139","img/organized_ascii/korean/MK-20251126-0139.png","N","소꼬리찜","0","9600","16000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0140","img/organized_ascii/korean/MK-20251126-0140.png","N","소불고기전골","0","13600","20000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0141","img/organized_ascii/korean/MK-20251126-0141.png","N","손말이고기","0","8600","13900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0142","img/organized_ascii/korean/MK-20251126-0142.png","N","숙성 양념 쪽갈비","0","11700","19000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0143","img/organized_ascii/korean/MK-20251126-0143.png","N","순대국","5","13200","20000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0144","img/organized_ascii/korean/MK-20251126-0144.png","N","알곤이찜","16","8800","13900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0145","img/organized_ascii/korean/MK-20251126-0145.png","N","양념 꼼장어","0","10600","16900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0146","img/organized_ascii/korean/MK-20251126-0146.png","N","양념 닭목살 볶음","10","9900","15000","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0147","img/organized_ascii/korean/MK-20251126-0147.png","N","양평해장국","0","13600","22000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0148","img/organized_ascii/korean/MK-20251126-0148.png","N","언양식 소불고기","0","15200","23900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0149","img/organized_ascii/korean/MK-20251126-0149.png","N","얼큰 낙곱새","0","14000","21900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0150","img/organized_ascii/korean/MK-20251126-0150.png","N","얼큰김치어묵우동전골","0","8600","12900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0151","img/organized_ascii/korean/MK-20251126-0151.png","N","오골계삼계탕","0","14300","23900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0152","img/organized_ascii/korean/MK-20251126-0152.png","N","오돌불갈비","0","6100","10000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0153","img/organized_ascii/korean/MK-20251126-0153.png","N","오돌뼈볶음","0","4500","7000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0154","img/organized_ascii/korean/MK-20251126-0154.png","N","오리 오고탕","0","15100","24900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0155","img/organized_ascii/korean/MK-20251126-0155.png","N","오징어볶음","0","6100","9900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0156","img/organized_ascii/korean/MK-20251126-0156.png","N","우렁강된장찌개","0","11900","18900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0157","img/organized_ascii/korean/MK-20251126-0157.png","N","우삼겹 버섯 손칼제비","23","11300","17000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0158","img/organized_ascii/korean/MK-20251126-0158.png","N","우삼겹 청국장","0","11900","17900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0159","img/organized_ascii/korean/MK-20251126-0159.png","N","이북식 만둣국","0","14500","23900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0160","img/organized_ascii/korean/MK-20251126-0160.png","N","재첩국","0","6600","10900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0161","img/organized_ascii/korean/MK-20251126-0161.png","N","전라도 묵은지","26","12900","19900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0162","img/organized_ascii/korean/MK-20251126-0162.png","N","전라도 파김치","0","4900","7900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0163","img/organized_ascii/korean/MK-20251126-0163.png","N","제육볶음","0","16000","24000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0164","img/organized_ascii/korean/MK-20251126-0164.png","N","즉석떡볶이","0","18600","27000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0165","img/organized_ascii/korean/MK-20251126-0165.png","N","짱뚱어탕","0","8800","13000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0166","img/organized_ascii/korean/MK-20251126-0166.png","N","쫄면","0","10400","16000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0167","img/organized_ascii/korean/MK-20251126-0167.png","N","쫄순대볶음","18","11100","16900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0168","img/organized_ascii/korean/MK-20251126-0168.png","N","쭈꾸미 삼겹살","0","16900","24900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0169","img/organized_ascii/korean/MK-20251126-0169.png","N","쭈꾸미볶음","0","14400","21000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0170","img/organized_ascii/korean/MK-20251126-0170.png","N","차돌떡볶이","0","12900","19900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0171","img/organized_ascii/korean/MK-20251126-0171.png","N","채소밀키트","0","10900","16900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0172","img/organized_ascii/korean/MK-20251126-0172.png","N","총각김치","0","16200","25000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0173","img/organized_ascii/korean/MK-20251126-0173.png","N","코다리조림","5","10200","17000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0174","img/organized_ascii/korean/MK-20251126-0174.png","N","콘치즈","0","10900","18000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0175","img/organized_ascii/korean/MK-20251126-0175.png","N","통마늘닭똥집볶음","0","11300","18900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0176","img/organized_ascii/korean/MK-20251126-0176.png","N","통영굴국","15","7200","12000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0177","img/organized_ascii/korean/MK-20251126-0177.png","N","한우 곰국","0","5300","7900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0178","img/organized_ascii/korean/MK-20251126-0178.png","N","한우 내장전골","0","9800","15900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0179","img/organized_ascii/korean/MK-20251126-0179.png","N","한우 우족탕","0","12500","19000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0180","img/organized_ascii/korean/MK-20251126-0180.png","N","한우우족탕","0","7300","10900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0181","img/organized_ascii/korean/MK-20251126-0181.png","N","한우육회","0","7700","11900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0182","img/organized_ascii/korean/MK-20251126-0182.png","N","해물 부추 부침개","0","4800","7900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0183","img/organized_ascii/korean/MK-20251126-0183.png","N","흑마늘 황칠염소탕","0","11100","16900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0184","img/organized/한식/MK-20251126-0184.png","N","감자옹심이 손칼국수","19","12800","18900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0185","img/organized/한식/MK-20251126-0185.png","N","감자탕","0","7100","10900","","","","","한식","","","","","","3","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0186","img/organized/한식/MK-20251126-0186.png","N","고추장 돼지불백","0","15100","24000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0187","img/organized/한식/MK-20251126-0187.png","N","곱창전골","5","16400","26900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0188","img/organized/한식/MK-20251126-0188.png","N","광양식 소불고기볶음밥","0","6600","10000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0189","img/organized/한식/MK-20251126-0189.png","N","김치 콩나물 국밥","14","17600","25900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0190","img/organized/한식/MK-20251126-0190.png","N","껍데기 소금구이","0","14400","23000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0191","img/organized/한식/MK-20251126-0191.png","N","꽃게탕 해물 된장찌개","0","7000","10000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0192","img/organized/한식/MK-20251126-0192.png","N","냉모밀소바","0","15000","23900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0193","img/organized/한식/MK-20251126-0193.png","N","냉채족발","0","17400","26000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0194","img/organized/한식/MK-20251126-0194.png","N","능이삼계탕","0","14000","20900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0195","img/organized/한식/MK-20251126-0195.png","N","닭내장탕","0","8700","12900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0196","img/organized/한식/MK-20251126-0196.png","N","닭발","0","4400","7000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0197","img/organized/한식/MK-20251126-0197.png","N","닭볶음탕","11","13200","20000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0198","img/organized/한식/MK-20251126-0198.png","N","대창김치찜","0","8700","13900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0199","img/organized/한식/MK-20251126-0199.png","N","도가니탕","0","4800","7900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0200","img/organized/한식/MK-20251126-0200.png","N","돌게&딱새우 된장찌개","0","12100","19000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0201","img/organized/한식/MK-20251126-0201.png","N","동태탕","0","7900","12000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0202","img/organized/한식/MK-20251126-0202.png","N","돼지 꼬리족발","0","8100","13000","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0203","img/organized/한식/MK-20251126-0203.png","N","돼지고기 김치찜","0","12800","20000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0204","img/organized/한식/MK-20251126-0204.png","N","돼지국밥","0","6100","9000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0205","img/organized/한식/MK-20251126-0205.png","N","들기름 막국수","15","6100","10000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0206","img/organized/한식/MK-20251126-0206.png","N","매콤 곱창볶음","9","11100","18000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0207","img/organized/한식/MK-20251126-0207.png","N","먹태구이","0","9000","14900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0208","img/organized/한식/MK-20251126-0208.png","N","멸치잔치국수","20","8800","13000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0209","img/organized/한식/MK-20251126-0209.png","N","물냉면","0","15800","24000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0210","img/organized/한식/MK-20251126-0210.png","N","민물 장어탕","0","13400","20000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0211","img/organized/한식/MK-20251126-0211.png","N","바지락 황태 칼국수","0","4800","7000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0212","img/organized/한식/MK-20251126-0212.png","N","바지락칼국수","0","6400","10000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0213","img/organized/한식/MK-20251126-0213.png","N","부대찌개","0","10800","16000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0214","img/organized/한식/MK-20251126-0214.png","N","부산약콩밀면","0","11000","17000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0215","img/organized/한식/MK-20251126-0215.png","N","불고기전골","0","7500","11000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0216","img/organized/한식/MK-20251126-0216.png","N","비빔 칼국수","12","11600","16900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0217","img/organized/한식/MK-20251126-0217.png","N","비빔낙지젓갈","0","16100","26000","","","","","한식","","","","","","2","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0218","img/organized/한식/MK-20251126-0218.png","N","비빔냉면과 왕만두","0","18200","26000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0219","img/organized/한식/MK-20251126-0219.png","N","뼈해장국","0","5000","8000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0220","img/organized/한식/MK-20251126-0220.png","N","사골시래기육개장","0","14400","20900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0221","img/organized/한식/MK-20251126-0221.png","N","생아귀탕","0","5600","8900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0222","img/organized/한식/MK-20251126-0222.png","N","선지해장국","0","15100","24900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0223","img/organized/한식/MK-20251126-0223.png","N","설렁탕","0","16600","26900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0224","img/organized/한식/MK-20251126-0224.png","N","소갈비탕","0","5500","7900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0225","img/organized/한식/MK-20251126-0225.png","N","소고기 국밥","0","16000","23900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0226","img/organized/한식/MK-20251126-0226.png","N","소고기 된장전골","0","17300","25900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0227","img/organized/한식/MK-20251126-0227.png","N","소고기 샤브샤브","0","15300","21900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0228","img/organized/한식/MK-20251126-0228.png","N","소고기미역국","0","5400","7900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0229","img/organized/한식/MK-20251126-0229.png","N","소곱창","0","14600","21900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0230","img/organized/한식/MK-20251126-0230.png","N","소꼬리찜","0","10100","16900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0231","img/organized/한식/MK-20251126-0231.png","N","소불고기전골","0","7900","12000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0232","img/organized/한식/MK-20251126-0232.png","N","손말이고기","0","10300","17000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0233","img/organized/한식/MK-20251126-0233.png","N","숙성 양념 쪽갈비","9","14900","24900","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0234","img/organized/한식/MK-20251126-0234.png","N","순대국","27","6300","9900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0235","img/organized/한식/MK-20251126-0235.png","N","알곤이찜","7","9200","14000","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0236","img/organized/한식/MK-20251126-0236.png","N","양념 꼼장어","0","17200","26900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0237","img/organized/한식/MK-20251126-0237.png","N","양념 닭목살 볶음","0","11900","19900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0238","img/organized/한식/MK-20251126-0238.png","N","양평해장국","0","17200","27900","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0239","img/organized/한식/MK-20251126-0239.png","N","언양식 소불고기","0","8900","14000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0240","img/organized/한식/MK-20251126-0240.png","N","얼큰 낙곱새","18","12800","20000","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0241","img/organized/한식/MK-20251126-0241.png","N","얼큰김치어묵우동전골","0","6300","9000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0242","img/organized/한식/MK-20251126-0242.png","N","오골계삼계탕","15","9600","16000","","","","","한식","","","","","","103","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0243","img/organized/한식/MK-20251126-0243.png","N","오돌불갈비","0","5400","8900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0244","img/organized/한식/MK-20251126-0244.png","N","오돌뼈볶음","0","9000","12900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0245","img/organized/한식/MK-20251126-0245.png","N","오리 오고탕","0","6100","10000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0246","img/organized/한식/MK-20251126-0246.png","N","오징어볶음","0","13100","19900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0247","img/organized/한식/MK-20251126-0247.png","N","우렁강된장찌개","0","18400","27900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0248","img/organized/한식/MK-20251126-0248.png","N","우삼겹 버섯 손칼제비","13","7300","11000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0249","img/organized/한식/MK-20251126-0249.png","N","우삼겹 청국장","0","14500","22000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0250","img/organized/한식/MK-20251126-0250.png","N","이북식 만둣국","20","9000","15000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0251","img/organized/한식/MK-20251126-0251.png","N","재첩국","0","13100","21900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0252","img/organized/한식/MK-20251126-0252.png","N","전라도 묵은지","0","15400","24900","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0253","img/organized/한식/MK-20251126-0253.png","N","전라도 파김치","27","9500","13900","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0254","img/organized/한식/MK-20251126-0254.png","N","제육볶음","0","5400","8900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0255","img/organized/한식/MK-20251126-0255.png","N","즉석떡볶이","0","4500","7000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0256","img/organized/한식/MK-20251126-0256.png","N","짱뚱어탕","0","7400","11000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0257","img/organized/한식/MK-20251126-0257.png","N","쫄면","0","16000","25900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0258","img/organized/한식/MK-20251126-0258.png","N","쫄순대볶음","0","6100","9000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0259","img/organized/한식/MK-20251126-0259.png","N","쭈꾸미 삼겹살","0","16500","25000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0260","img/organized/한식/MK-20251126-0260.png","N","쭈꾸미볶음","0","5800","8900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0261","img/organized/한식/MK-20251126-0261.png","N","차돌떡볶이","0","12100","19000","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0262","img/organized/한식/MK-20251126-0262.png","N","채소밀키트","0","17300","25900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0263","img/organized/한식/MK-20251126-0263.png","N","총각김치","0","8700","12900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0264","img/organized/한식/MK-20251126-0264.png","N","코다리조림","12","11800","18000","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0265","img/organized/한식/MK-20251126-0265.png","N","콘치즈","0","15000","23900","","","","","한식","","","","","","34","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0266","img/organized/한식/MK-20251126-0266.png","N","통마늘닭똥집볶음","0","8900","14000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0267","img/organized/한식/MK-20251126-0267.png","N","통영굴국","0","13400","22000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0268","img/organized/한식/MK-20251126-0268.png","N","한우 곰국","0","17500","27000","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0269","img/organized/한식/MK-20251126-0269.png","N","한우 내장전골","11","14000","20900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0270","img/organized/한식/MK-20251126-0270.png","N","한우 우족탕","0","6300","9900","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0271","img/organized/한식/MK-20251126-0271.png","N","한우우족탕","0","12000","18000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0272","img/organized/한식/MK-20251126-0272.png","N","한우육회","0","17500","27000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0273","img/organized/한식/MK-20251126-0273.png","N","해물 부추 부침개","0","5900","9900","","","","","한식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0274","img/organized/한식/MK-20251126-0274.png","N","흑마늘 황칠염소탕","0","7900","12000","","","","","한식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0275","img/organized/중식/MK-20251126-0275.png","N","고추잡채와 꽃빵","20","13200","21000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0276","img/organized/중식/MK-20251126-0276.png","N","교동짬뽕","0","15800","22900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0277","img/organized/중식/MK-20251126-0277.png","N","깐풍기","0","6100","10000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0278","img/organized/중식/MK-20251126-0278.png","N","꿔바로우","0","7700","11900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0279","img/organized/중식/MK-20251126-0279.png","N","동파육","0","11000","17000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0280","img/organized/중식/MK-20251126-0280.png","N","동파육만두","0","9000","15000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0281","img/organized/중식/MK-20251126-0281.png","N","마라 바지락 볶음","0","11200","16000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0282","img/organized/중식/MK-20251126-0282.png","N","마라샹궈","0","9000","13900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0283","img/organized/중식/MK-20251126-0283.png","N","마라탕","0","5100","8000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0284","img/organized/중식/MK-20251126-0284.png","N","마파두부","0","13200","18900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0285","img/organized/중식/MK-20251126-0285.png","N","삼겹 동파육","0","8500","12900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0286","img/organized/중식/MK-20251126-0286.png","N","양고기","0","9200","14900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0287","img/organized/중식/MK-20251126-0287.png","N","쟁반짜장","0","12000","18000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0288","img/organized/중식/MK-20251126-0288.png","N","중화풍 양장피","0","8700","13900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0289","img/organized/중식/MK-20251126-0289.png","N","중화풍 팔보채","28","13800","22000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0290","img/organized/중식/MK-20251126-0290.png","N","칠리새우","0","6700","11000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0291","img/organized/중식/MK-20251126-0291.png","N","티앤미미 동파육","0","6300","9900","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0292","img/organized/중식/MK-20251126-0292.png","N","티앤미미 비빔새우","0","12200","20000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0293","img/organized/중식/MK-20251126-0293.png","N","티앤미미 새우완탕면","0","13800","21000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0294","img/organized/중식/MK-20251126-0294.png","N","티앤미미 우삼겹차우면","6","12500","20900","","","","","중식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0295","img/organized/중식/MK-20251126-0295.png","N","향라새우","0","4200","7000","","","","","중식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0296","img/organized/일식/MK-20251126-0296.png","N","규동 덮밥","0","13700","21900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0297","img/organized/일식/MK-20251126-0297.png","N","김재현 홈마카세 스시 밀키트","0","14800","23900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0298","img/organized/일식/MK-20251126-0298.png","N","돈코츠 라멘","12","7000","10900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0299","img/organized/일식/MK-20251126-0299.png","N","등심돈까스","0","7800","11900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0300","img/organized/일식/MK-20251126-0300.png","N","명란구이","0","11700","18900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0301","img/organized/일식/MK-20251126-0301.png","N","명란크림소바","0","21600","34900","","","","","일식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0302","img/organized/일식/MK-20251126-0302.png","N","모츠나베","0","13500","19900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0303","img/organized/일식/MK-20251126-0303.png","N","문어가라아게","0","19600","28900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0304","img/organized/일식/MK-20251126-0304.png","N","밀푀유나베","11","18800","29000","","","","","일식","","","","","","4","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0305","img/organized/일식/MK-20251126-0305.png","N","바질 고르곤졸라 뇨끼","0","14700","23000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0306","img/organized/일식/MK-20251126-0306.png","N","베이컨 야끼소바","11","24500","35000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0307","img/organized/일식/MK-20251126-0307.png","N","사누끼우동면","0","14800","24000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0308","img/organized/일식/MK-20251126-0308.png","N","삿포로생라멘면","0","19500","31000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0309","img/organized/일식/MK-20251126-0309.png","N","소고기 규동 밀키트","0","17000","28000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0310","img/organized/일식/MK-20251126-0310.png","N","소고기 스키야키","0","19200","31000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0311","img/organized/일식/MK-20251126-0311.png","N","소고기 야끼소바","0","17700","26900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0312","img/organized/일식/MK-20251126-0312.png","N","야끼찌꾸와","0","7300","10900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0313","img/organized/일식/MK-20251126-0313.png","N","야키토리 수제꼬치","0","13700","19900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0314","img/organized/일식/MK-20251126-0314.png","N","양념장어덮밥","0","9600","15900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0315","img/organized/일식/MK-20251126-0315.png","N","오뎅꼬치","0","6700","9900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0316","img/organized/일식/MK-20251126-0316.png","N","장어구이","0","5400","8900","","","","","일식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0317","img/organized/일식/MK-20251126-0317.png","N","치킨 가라아게","0","11600","17900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0318","img/organized/일식/MK-20251126-0318.png","N","타코야끼","0","11500","19000","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0319","img/organized/일식/MK-20251126-0319.png","N","타코와사비","0","7100","11900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0320","img/organized/일식/MK-20251126-0320.png","N","트러플 크림 뇨끼","0","21600","31900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0321","img/organized/일식/MK-20251126-0321.png","N","해물 오코노미야끼","20","8500","12900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0322","img/organized/일식/MK-20251126-0322.png","N","헤믈 나가사키 짬뽕","0","12300","19900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0323","img/organized/일식/MK-20251126-0323.png","N","호쿠쇼쿠 타코와사비","0","16600","26900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0324","img/organized/일식/MK-20251126-0324.png","N","홍게 오뎅탕","18","15100","24900","","","","","일식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0325","img/organized/양식/MK-20251126-0325.png","N","가리비 바질파스타","0","18600","30000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0326","img/organized/양식/MK-20251126-0326.png","N","갈릭비프스테이크","0","20100","30000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0327","img/organized/양식/MK-20251126-0327.png","N","감바스","0","17900","28900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0328","img/organized/양식/MK-20251126-0328.png","N","고르곤졸라피자","0","17700","26900","","","","","양식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0329","img/organized/양식/MK-20251126-0329.png","N","매운 크림 리조또","0","13100","20900","","","","","양식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0330","img/organized/양식/MK-20251126-0330.png","N","매운 크림 파스타","0","11200","17000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0331","img/organized/양식/MK-20251126-0331.png","N","묵은지 들기름 파스타","0","14800","22900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0332","img/organized/양식/MK-20251126-0332.png","N","베이컨토마토파스타","0","9900","16000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0333","img/organized/양식/MK-20251126-0333.png","N","봉골레 파스타","0","8900","13000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0334","img/organized/양식/MK-20251126-0334.png","N","빕스 부채살 찹스테이크","0","12700","19000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0335","img/organized/양식/MK-20251126-0335.png","N","빕스 클래식 스테이크","0","7600","11900","","","","","양식","","","","","","99","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0336","img/organized/양식/MK-20251126-0336.png","N","새우명란오일파스타","0","16900","26900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0337","img/organized/양식/MK-20251126-0337.png","N","새우봉골레 파스타","0","13600","22000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0338","img/organized/양식/MK-20251126-0338.png","N","생크림 새우 리조또","0","18600","27000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0339","img/organized/양식/MK-20251126-0339.png","N","생크림 새우 파스타","0","10200","17000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0340","img/organized/양식/MK-20251126-0340.png","N","알렌 뼈등심스테이크&페퍼콘소스","0","15100","22900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0341","img/organized/양식/MK-20251126-0341.png","N","알렌 스피니치페스토 에그생면파스타","5","14000","20900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0342","img/organized/양식/MK-20251126-0342.png","N","알렌 아라비아따 에그생면파스타","0","9300","13900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0343","img/organized/양식/MK-20251126-0343.png","N","알리오 올리오 파스타","0","13000","21000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0344","img/organized/양식/MK-20251126-0344.png","N","차돌박이 마늘쫑 오일파스타","0","16800","24000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0345","img/organized/양식/MK-20251126-0345.png","N","찹스테이크","0","16300","26000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0346","img/organized/양식/MK-20251126-0346.png","N","트러플크림 뇨끼","0","19800","30000","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0347","img/organized/양식/MK-20251126-0347.png","N","푸타네스카 파스타","0","16500","25900","","","","","양식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0348","img/organized/양식/MK-20251126-0348.png","N","해산물 토마토 파스타","0","9800","14000","","","","","양식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0349","img/organized/양식/MK-20251126-0349.png","N","화이트 라구 파스타","0","15600","26000","","","","","양식","","","","","","2","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0350","img/organized/건강식/MK-20251126-0350.png","N","그릴드 닭가슴살 포케","0","6800","10000","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0351","img/organized/건강식/MK-20251126-0351.png","N","누룽지 삼계탕","0","6400","9900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0352","img/organized/건강식/MK-20251126-0352.png","N","능이 오리백숙","15","8000","11900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0353","img/organized/건강식/MK-20251126-0353.png","N","닭가슴살 도시락","0","4300","7000","","","","","건강식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0354","img/organized/건강식/MK-20251126-0354.png","N","닭가슴살","26","5900","9900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0355","img/organized/건강식/MK-20251126-0355.png","N","목살 스테이크 샐러드","0","6400","9900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0356","img/organized/건강식/MK-20251126-0356.png","N","보양식 해신탕","0","7400","11900","","","","","건강식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0357","img/organized/건강식/MK-20251126-0357.png","N","샐러드 마녀 비프포케 샐러드","0","4600","6900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0358","img/organized/건강식/MK-20251126-0358.png","N","세끼판다 단호박 샐러드","0","3700","5900","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0359","img/organized/건강식/MK-20251126-0359.png","N","쉐이크 비프 포케 샐러드","0","4500","7000","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0360","img/organized/건강식/MK-20251126-0360.png","N","쉐이크 쉬림프 포케 샐러드","0","8800","14000","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0361","img/organized/건강식/MK-20251126-0361.png","N","오븐 닭가슴살","0","4300","7000","","","","","건강식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0362","img/organized/건강식/MK-20251126-0362.png","N","월남쌈","0","8300","13000","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0363","img/organized/건강식/MK-20251126-0363.png","N","전복죽","0","7000","11000","","","","","건강식","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0364","img/organized/건강식/MK-20251126-0364.png","N","추어탕","0","6800","10000","","","","","건강식","","","","","","0","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0365","img/organized/기타/MK-20251126-0365.png","N","건어물 5종세트","17","3700","6000","","","","","기타","","","","","","1","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251126-0366","img/organized/기타/MK-20251126-0366.png","N","슈크림 붕어빵","0","6900","10900","","","","","기타","","","","","","2","","","2025-12-03 13:53:05");
INSERT INTO contents VALUES("MK-20251203-0001","img/organized/기타/MK-20251203-0001.jpg","N","테스트","0","59999999","99999999","","","","","기타","","","","","","2","테스트","dnf826","2025-12-03 14:05:49");
INSERT INTO contents VALUES("MK-20251204-0001","img/organized/한식/MK-20251204-0001.webp","N","이현곤표 제육볶음","0","4680000","7800000","","","","","한식","","","","","","3","","공준영입니다","2025-12-04 10:30:51");
INSERT INTO contents VALUES("MK-20251204-0002","img/organized/건강식/MK-20251204-0002.jpg","N","카리나","0","2147483647","2147483647","","","","","건강식","","","","","","1","","ㅇ현곤","2025-12-04 10:32:19");
INSERT INTO contents VALUES("MK-20251204-0003","img/organized/한식/MK-20251204-0003.jfif","N","이현곤표 김치 볶음밥","0","3120000","5200000","","","","","한식","","","","","","2","","공준영입니다","2025-12-04 10:32:45");
INSERT INTO contents VALUES("MK-20251204-0004","img/organized/중식/MK-20251204-0004.jpg","N","화사","0","0","1","","","","","중식","","","","","","0","ㅋㅋㅋ","ㅇ현곤","2025-12-04 10:33:38");
INSERT INTO contents VALUES("MK-20251204-0005","img/organized/한식/MK-20251204-0005.jpg","N","이현곤표 김치찌개","0","5160000","8600000","","","","","한식","","","","","","1","","공준영입니다","2025-12-04 10:34:21");
INSERT INTO contents VALUES("MK-20251204-0006","img/organized/한식/MK-20251204-0006.jpg","N","이현곤표 된장찌개","0","5220000","8700000","","","","","한식","","","","","","2","","공준영입니다","2025-12-04 10:36:32");
INSERT INTO contents VALUES("MK-20251204-0007","img/organized/양식/MK-20251204-0007.png","N","이현곤표 트러플 파스타","0","7560000","12600000","","","","","양식","","","","","","2","만들어드립니다","공준영입니다","2025-12-04 10:38:10");
INSERT INTO contents VALUES("MK-20251204-0008","img/organized/한식/MK-20251204-0008.jpg","N","이현곤표 라면","0","22200000","37000000","","","","","한식","","","","","","2","라면 먹고 갈래?","공준영입니다","2025-12-04 10:39:29");
INSERT INTO contents VALUES("MK-20251204-0009","img/organized/기타/MK-20251204-0009.jpg","N","이현곤","0","2147483647","2147483647","","","","","기타","","","","","","0","","반정우입니다.","2025-12-04 10:50:23");
INSERT INTO contents VALUES("MK-20251204-0010","img/organized/한식/MK-20251204-0010.jpg","N","이현곤표 부대찌개","0","5580000","9300000","","","","","한식","","","","","","0","","현곤","2025-12-04 11:34:36");
INSERT INTO contents VALUES("MK-20251204-0011","img/organized/한식/MK-20251204-0011.jpg","N","이현곤표 소불고기","0","5987400","9979000","","","","","한식","","","","","","3","","현곤","2025-12-04 11:41:00");



DROP TABLE IF EXISTS delivery_address;

CREATE TABLE `delivery_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` varchar(50) NOT NULL,
  `recipient_name` varchar(50) NOT NULL,
  `recipient_phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `address_detail` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO delivery_address VALUES("1","dnf826","김우림","01064019455","서울 강동구 가래여울길 1","","1","2025-12-06 17:50:03");
INSERT INTO delivery_address VALUES("2","dnf826","김우림","0106016","경기 성남시 중원구 광명로 377","","0","2025-12-06 17:50:22");



DROP TABLE IF EXISTS faqs;

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO faqs VALUES("1","주문/결제","주문을 취소하고 싶어요.","주문 취소는 마이페이지 > 주문내역에서 가능합니다. 배송 준비 중일 경우 고객센터로 문의해주세요.","2025-12-03 15:12:47");
INSERT INTO faqs VALUES("2","배송","배송지 변경은 어떻게 하나요?","배송지 변경은 주문 상태가 입금대기, 결제완료 상태일 때만 가능합니다.","2025-12-03 15:12:47");
INSERT INTO faqs VALUES("3","상품","신선식품 보관 방법이 궁금해요.","수령 즉시 냉장 보관해주시기 바랍니다.","2025-12-03 15:12:47");
INSERT INTO faqs VALUES("4","배송","제주/도서산간 지역 배송 안내","제주 및 도서산간 지역은 추가 배송비가 발생할 수 있습니다.","2025-12-03 15:12:47");
INSERT INTO faqs VALUES("5","교환/반품","상품에 문제가 있어요.","상품 수령 후 7일 이내에 고객센터로 연락 주시면 교환/반품 절차를 안내해 드립니다.","2025-12-03 15:12:47");



DROP TABLE IF EXISTS inquiries;

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `answer` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'waiting',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO inquiries VALUES("1","dnf826","주문/결제","테스트","테스트","테스트확인완료","answered","2025-12-04 09:48:39");
INSERT INTO inquiries VALUES("2","kong","취소/반품","이현곤","이현곤 처리좀","오늘 피싸게해줄게","answered","2025-12-04 12:10:14");



DROP TABLE IF EXISTS members;

CREATE TABLE `members` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `id` char(15) NOT NULL,
  `pass` varchar(450) NOT NULL,
  `name` char(10) NOT NULL,
  `phone` char(20) NOT NULL,
  `birth` char(20) DEFAULT NULL,
  `email` char(80) DEFAULT NULL,
  `refferer` char(15) DEFAULT NULL,
  `regist_day` char(20) NOT NULL,
  `level` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO members VALUES("13","ga","$2y$10$hv5o2iKyLfDmOjHWf.kpSOj/i7LWyrz.8vHA0YX7UuUHdfRUkAHBS","ga","0104545445","","@","","2022-11-28 (05:28)","9","0");
INSERT INTO members VALUES("14","join","$2y$10$3/8Ser2GpI1xkFb.rmk07OUWbUPYscFSGLOmXbiKJJRXMr5N79SCy","join","2132","","@","","2022-12-08 (04:58)","9","0");
INSERT INTO members VALUES("15","j","$2y$10$bZKYOQgpQSkIcaSAvnmkuehOFXyCtHqaJPamtW/jvJfLuTuF4HMYq","j","1010","","@","","2022-12-08 (05:00)","9","0");
INSERT INTO members VALUES("16","dnf826","$2y$10$KOW/I4/stMZmVMdj2z.TXu2l7yaVJXvvWMXCw.AL8dW95GlF8Di1C","김우림","00000000000","030826","","","2025-11-13 (02:23)","1","1940");
INSERT INTO members VALUES("17","rlatngla","$2y$10$IoncI.lrALgHCQm/9zp1..i.ITjGHLrSrOjxx7jvWnLUDRiGLGKR6","김수현","01064019455","051124","bagle97@naver.com","ghfg","2025-11-20 (05:29)","9","0");
INSERT INTO members VALUES("18","현곤","$2y$10$0vw0LpZvF.QseY3Q2Cj2q.YYM5yChwO2ObiGprdZ7MqfQDnXQSWTy","현곤","01052660402","303018","","공준영가슴","2025-11-27 (03:13)","2","2000");
INSERT INTO members VALUES("19","kong","$2y$10$mqnrQK/OiCFdlD.Y5k.0MOMbFHPY8zTr52rrZOqJo5eIaRctGCFja","공준영","01035260680","030908","3123@412","12314","2025-11-27 (03:23)","2","1510");
INSERT INTO members VALUES("20","ksh03029","$2y$10$5U8Yubr9pE6G4JvYwb09xOQgVxZi0ATDzIYD3vsBdHMc5npXCPGmq","반정우","01049288891","20020619","naver.com","","2025-11-27 (03:25)","9","0");
INSERT INTO members VALUES("21","반정우입니다","$2y$10$dInWLY18RteYFtAhfZINAOrw3.0Qjg9OxRuJtMOxxaQJ5M98E1C8C","반정우","01052660402","","","","2025-11-27 (03:26)","9","0");
INSERT INTO members VALUES("22","ldd72","$2y$10$Hpa0gv4Vsi7oCcW/a6nyNOUfdo6BLrN3ZQxjZHKIVUoFnIyijFfQW","이동찬","01034472884","20020826","ldd82@naver.com","","2025-11-27 (03:29)","9","0");
INSERT INTO members VALUES("23","sexyguy","$2y$10$jiAff541nWezWEo1lfAKeei5SF1Q2pGz9COrpJ7rxhU8oNRECQ5Ie","강민권","01021730477","","","","2025-11-27 (03:33)","9","0");
INSERT INTO members VALUES("24","이현곤","$2y$10$NtjR2uGCOM8.rQXSnK.G0.EVtwOQKdvL1AS9S5PRR0DCAdVmgi.0K","반정우","01049288891","20020619","zkdkkdjd12@naver.com","","2025-11-27 (03:38)","9","0");
INSERT INTO members VALUES("25","2현곤","$2y$10$tkyM45UMsZHgKh.H/Dgvd.tXWaDZE.radGmeRwr0YcOW34VnqdroW","반정우","01049288891","","","","2025-11-27 (03:41)","9","0");
INSERT INTO members VALUES("26","bjw0619","$2y$10$LCWUhkH/5KDrd3u2ASLP6uQR3kgTIjmNR7oUdM.xwBfR5hPi99w4W","현곤","01049288891","","","","2025-11-27 (03:42)","9","0");
INSERT INTO members VALUES("27","ㅇ현곤","$2y$10$K/Frwsd4kbak9YsZAD0H0.I7PGsu4JJuO74epCGwbyjMaqG.pYb0.","이현곤","01049288891","","","","2025-11-27 (03:44)","2","0");
INSERT INTO members VALUES("28","카리나","$2y$10$d6Ow841lbInntwnCE1amouHiPaTFYZvejXWrVz5W7lgDvzAV6cgR2","카리나","1323124123","3142","31242@23123","3123","2025-11-27 (03:47)","9","0");
INSERT INTO members VALUES("29","반정우입니다.","$2y$10$YZkem.Hjd.oSgzE86Kx1D.f/3BZFHoRlQiC2OD2Im3STfcWpaVbCS","반정우","01052660402","20020318","dlgusrhs0402","","2025-12-04 (01:15)","9","0");
INSERT INTO members VALUES("30","공준영입니다","$2y$10$FS4LnfH2qAyFGI1o.Xkj/.RnjI0L8A4CffqrOq5JUEjcfaZE4xv5e","공준영","01052660402","","","","2025-12-04 (01:25)","9","0");
INSERT INTO members VALUES("31","승준","$2y$10$HLeLGtZmNj4Ed1eofBs2T..OogZCWwdEXURelgR2jANB66SjQK8Lm","이승준","010112312312321","","","","2025-12-04 (01:45)","9","0");
INSERT INTO members VALUES("32","서아","$2y$10$YBKP.f134xsjwOF/gdU5NO5MYyxv2G547cebTQQjsz3keEDbdmOsi","김서아","12312313123123","","","","2025-12-04 (01:55)","9","0");
INSERT INTO members VALUES("33","dd","$2y$10$TtVMgTVgMB8jMzAmhVhTu.431W7mUjk9IAb1ZK56ZD.v92rvGDmqK","ㅎㅈ","01011111111","","","","2025-12-08 (08:55)","9","0");
INSERT INTO members VALUES("34","123","$2y$10$37NWvQIlv1K3.WlL4GPN0e7xRCiOEYPCVWwBYjHZdMPNYxpEukTGG","김우림","123","","123@naver.com","","","0","0");
INSERT INTO members VALUES("35","d","$2y$10$QCnJNoO8JU995ulj5116sOIrxCZ1rcG1juadrzXpQir23pSXNffaO","d","00000000000","","","","2025-12-08 (10:07)","9","0");



DROP TABLE IF EXISTS notice;

CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `writer` varchar(50) NOT NULL,
  `views` int(11) DEFAULT 0,
  `reg_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO notice VALUES("1","서비스 점검 안내","새벽 2시부터 4시까지 점검이 있습니다.","admin","0","2025-12-04 09:39:25");
INSERT INTO notice VALUES("2","신규 회원 혜택 안내","가입 시 3000포인트 지급!","admin","0","2025-12-04 09:39:25");
INSERT INTO notice VALUES("3","테스트","테스트입니다.","김우림","0","2025-12-04 09:41:54");
INSERT INTO notice VALUES("4","테스트2","테스트입니다.","김우림","0","2025-12-04 09:46:44");
INSERT INTO notice VALUES("5","다 나가","고생하십쇼","공준영","0","2025-12-04 12:01:10");
INSERT INTO notice VALUES("6","공지테스트","111","김우림","0","2025-12-06 17:57:11");



DROP TABLE IF EXISTS notices;

CREATE TABLE `notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `writer` varchar(50) DEFAULT 'Admin',
  `views` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO notices VALUES("1","추석 연휴 배송 마감 안내","추석 연휴로 인해 9월 10일 배송이 마감됩니다.","Admin","0","2025-09-01 10:00:00");
INSERT INTO notices VALUES("2","신규 회원 가입 혜택 안내","신규 회원 가입 시 3,000원 쿠폰을 드립니다.","Admin","0","2025-08-01 09:00:00");
INSERT INTO notices VALUES("3","시스템 점검 안내","새벽 2시부터 4시까지 시스템 점검이 있습니다.","Admin","0","2025-10-05 14:00:00");



DROP TABLE IF EXISTS pay;

CREATE TABLE `pay` (
  `order_id` varchar(50) NOT NULL,
  `orderer_name` char(10) NOT NULL,
  `orderer_email` char(80) NOT NULL,
  `orderer_phone` char(20) NOT NULL,
  `Recipient_name` char(10) NOT NULL,
  `zip_code` char(5) NOT NULL,
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `Recipient_phone` char(20) NOT NULL,
  `message` varchar(20) DEFAULT NULL,
  `member_id` char(15) NOT NULL,
  `order_contents` longtext DEFAULT NULL,
  `review` char(1) NOT NULL DEFAULT 'N',
  `status` varchar(20) DEFAULT '결제완료',
  `total_price` int(11) DEFAULT 0,
  `order_date` datetime DEFAULT current_timestamp(),
  `used_point` int(11) DEFAULT 0,
  PRIMARY KEY (`order_id`),
  KEY `idx_pay_member_id` (`member_id`),
  KEY `idx_pay_order_date` (`order_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO pay VALUES("202511270256463749","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251126-0240\",\"content_name\":\"얼큰 낙곱새\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0240.png\",\"content_options\":\"얼큰 낙곱새\",\"content_amount\":1,\"content_price\":40000,\"total_price\":40000}]","Y","구매확정","40000","2025-11-27 10:56:46","0");
INSERT INTO pay VALUES("202511270300512004","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251126-0265\",\"content_name\":\"콘치즈\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0265.png\",\"content_options\":\"콘치즈\",\"content_amount\":1,\"content_price\":40000,\"total_price\":40000}]","N","구매확정","40000","2025-11-27 11:00:51","0");
INSERT INTO pay VALUES("202511270300565516","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251126-0304\",\"content_name\":\"밀푀유나베\",\"content_img\":\"img\\/organized\\/일식\\/MK-20251126-0304.png\",\"content_options\":\"밀푀유나베\",\"content_amount\":1,\"content_price\":40000,\"total_price\":40000}]","N","결제완료","40000","2025-11-27 11:00:56","0");
INSERT INTO pay VALUES("202511270301037202","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251126-0304\",\"content_name\":\"밀푀유나베\",\"content_img\":\"img\\/organized\\/일식\\/MK-20251126-0304.png\",\"content_options\":\"밀푀유나베\",\"content_amount\":1,\"content_price\":40000,\"total_price\":40000}]","N","결제완료","40000","2025-11-27 11:01:03","0");
INSERT INTO pay VALUES("202511270301094620","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251126-0265\",\"content_name\":\"콘치즈\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0265.png\",\"content_options\":\"콘치즈\",\"content_amount\":6,\"content_price\":40000,\"total_price\":240000}]","N","결제완료","240000","2025-11-27 11:01:09","0");
INSERT INTO pay VALUES("202511270314027329","","","","","","","","","","현곤","[{\"content_code\":\"MK-20251126-0366\",\"content_name\":\"슈크림 붕어빵\",\"content_img\":\"img\\/organized\\/기타\\/MK-20251126-0366.png\",\"content_options\":\"슈크림 붕어빵\",\"content_amount\":1,\"content_price\":26000,\"total_price\":26000}]","N","결제완료","29000","2025-11-27 11:14:02","0");
INSERT INTO pay VALUES("202511270314267524","","","","","","","","","","현곤","[{\"content_code\":\"MK-20251126-0265\",\"content_name\":\"콘치즈\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0265.png\",\"content_options\":\"콘치즈\",\"content_amount\":26,\"content_price\":40000,\"total_price\":1040000}]","Y","구매확정","1040000","2025-11-27 11:14:26","0");
INSERT INTO pay VALUES("202511270323289370","","","","","","","","","","현곤","[{\"content_code\":\"MK-20251126-0365\",\"content_name\":\"건어물 5종세트\",\"content_img\":\"img\\/organized\\/기타\\/MK-20251126-0365.png\",\"content_options\":\"건어물 5종세트\",\"content_amount\":1,\"content_price\":11000,\"total_price\":11000}]","Y","구매확정","14000","2025-11-27 11:23:28","0");
INSERT INTO pay VALUES("202511270323564560","","","","","","","","","","kong","[{\"content_code\":\"MK-20251126-0185\",\"content_name\":\"감자탕\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0185.png\",\"content_options\":\"감자탕\",\"content_amount\":1,\"content_price\":40000,\"total_price\":40000}]","Y","구매확정","40000","2025-11-27 11:23:56","0");
INSERT INTO pay VALUES("202511270326121058","","","","","","","","","","ksh03029","[{\"content_code\":\"MK-20251126-0202\",\"content_name\":\"돼지 꼬리족발\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0202.png\",\"content_options\":\"돼지 꼬리족발\",\"content_amount\":1,\"content_price\":38000,\"total_price\":38000}]","Y","구매확정","41000","2025-11-27 11:26:12","0");
INSERT INTO pay VALUES("202511270327047941","","","","","","","","","","kong","[{\"content_code\":\"MK-20251126-0301\",\"content_name\":\"명란크림소바\",\"content_img\":\"img\\/organized\\/일식\\/MK-20251126-0301.png\",\"content_options\":\"명란크림소바\",\"content_amount\":1,\"content_price\":39000,\"total_price\":39000}]","Y","구매확정","42000","2025-11-27 11:27:04","0");
INSERT INTO pay VALUES("202511270327063306","","","","","","","","","","반정우입니다","[{\"content_code\":\"MK-20251126-0363\",\"content_name\":\"전복죽\",\"content_img\":\"img\\/organized\\/건강식\\/MK-20251126-0363.png\",\"content_options\":\"전복죽\",\"content_amount\":1,\"content_price\":24000,\"total_price\":24000}]","Y","구매확정","27000","2025-11-27 11:27:06","0");
INSERT INTO pay VALUES("202511270327496512","","","","","","","","","","ksh03029","[{\"content_code\":\"MK-20251126-0080\",\"content_name\":\"마라탕\",\"content_img\":\"img\\/organized_ascii\\/chinese\\/MK-20251126-0080.png\",\"content_options\":\"마라탕\",\"content_amount\":1,\"content_price\":13800,\"total_price\":13800}]","Y","구매확정","16800","2025-11-27 11:27:49","0");
INSERT INTO pay VALUES("202511270327538599","","","","","","","","","","반정우입니다","[{\"content_code\":\"MK-20251126-0361\",\"content_name\":\"오븐 닭가슴살\",\"content_img\":\"img\\/organized\\/건강식\\/MK-20251126-0361.png\",\"content_options\":\"오븐 닭가슴살\",\"content_amount\":1,\"content_price\":18000,\"total_price\":18000}]","Y","구매확정","21000","2025-11-27 11:27:53","0");
INSERT INTO pay VALUES("202511270329291839","","","","","","","","","","kong","[{\"content_code\":\"MK-20251126-0235\",\"content_name\":\"알곤이찜\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0235.png\",\"content_options\":\"알곤이찜\",\"content_amount\":1,\"content_price\":37000,\"total_price\":37000}]","Y","구매확정","40000","2025-11-27 11:29:29","0");
INSERT INTO pay VALUES("202511270330482063","","","","","","","","","","반정우입니다","[{\"content_code\":\"MK-20251126-0252\",\"content_name\":\"전라도 묵은지\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0252.png\",\"content_options\":\"전라도 묵은지\",\"content_amount\":1,\"content_price\":21000,\"total_price\":21000}]","Y","구매확정","24000","2025-11-27 11:30:48","0");
INSERT INTO pay VALUES("202511270332053355","","","","","","","","","","반정우입니다","[{\"content_code\":\"MK-20251126-0217\",\"content_name\":\"비빔낙지젓갈\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0217.png\",\"content_options\":\"비빔낙지젓갈\",\"content_amount\":1,\"content_price\":23000,\"total_price\":23000}]","N","구매확정","26000","2025-11-27 11:32:05","0");
INSERT INTO pay VALUES("202511270333038335","","","","","","","","","","반정우입니다","[{\"content_code\":\"MK-20251126-0217\",\"content_name\":\"비빔낙지젓갈\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0217.png\",\"content_options\":\"비빔낙지젓갈\",\"content_amount\":1,\"content_price\":23000,\"total_price\":23000}]","Y","구매확정","26000","2025-11-27 11:33:03","0");
INSERT INTO pay VALUES("202511270333131101","","","","","","","","","","ldd72","[{\"content_code\":\"MK-20251126-0265\",\"content_name\":\"콘치즈\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0265.png\",\"content_options\":\"콘치즈\",\"content_amount\":1,\"content_price\":40000,\"total_price\":40000}]","Y","구매확정","40000","2025-11-27 11:33:13","0");
INSERT INTO pay VALUES("202511270333554515","","","","","","","","","","sexyguy","[{\"content_code\":\"MK-20251126-0349\",\"content_name\":\"화이트 라구 파스타\",\"content_img\":\"img\\/organized\\/양식\\/MK-20251126-0349.png\",\"content_options\":\"화이트 라구 파스타\",\"content_amount\":1,\"content_price\":24000,\"total_price\":24000}]","Y","구매확정","27000","2025-11-27 11:33:55","0");
INSERT INTO pay VALUES("202511270335101041","","","","","","","","","","반정우입니다","[{\"content_code\":\"MK-20251126-0328\",\"content_name\":\"고르곤졸라피자\",\"content_img\":\"img\\/organized\\/양식\\/MK-20251126-0328.png\",\"content_options\":\"고르곤졸라피자\",\"content_amount\":1,\"content_price\":27000,\"total_price\":27000}]","Y","구매확정","30000","2025-11-27 11:35:10","0");
INSERT INTO pay VALUES("202511270336335544","","","","","","","","","","ksh03029","[{\"content_code\":\"MK-20251126-0100\",\"content_name\":\"꽃게탕 해물 된장찌개\",\"content_img\":\"img\\/organized_ascii\\/korean\\/MK-20251126-0100.png\",\"content_options\":\"꽃게탕 해물 된장찌개\",\"content_amount\":1,\"content_price\":20700,\"total_price\":20700}]","Y","구매확정","23700","2025-11-27 11:36:33","0");
INSERT INTO pay VALUES("202511270337529388","","","","","","","","","","반정우입니다","[{\"content_code\":\"MK-20251126-0316\",\"content_name\":\"장어구이\",\"content_img\":\"img\\/organized\\/일식\\/MK-20251126-0316.png\",\"content_options\":\"장어구이\",\"content_amount\":1,\"content_price\":21000,\"total_price\":21000}]","Y","구매확정","24000","2025-11-27 11:37:52","0");
INSERT INTO pay VALUES("202511270338205647","","","","","","","","","","sexyguy","[{\"content_code\":\"MK-20251126-0185\",\"content_name\":\"감자탕\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0185.png\",\"content_options\":\"감자탕\",\"content_amount\":1,\"content_price\":40000,\"total_price\":40000}]","Y","구매확정","40000","2025-11-27 11:38:20","0");
INSERT INTO pay VALUES("202511270339198182","","","","","","","","","","sexyguy","[{\"content_code\":\"MK-20251126-0366\",\"content_name\":\"슈크림 붕어빵\",\"content_img\":\"img\\/organized\\/기타\\/MK-20251126-0366.png\",\"content_options\":\"슈크림 붕어빵\",\"content_amount\":1,\"content_price\":26000,\"total_price\":26000}]","Y","구매확정","29000","2025-11-27 11:39:19","0");
INSERT INTO pay VALUES("202511270341122273","","","","","","","","","","반정우입니다","[{\"content_code\":\"MK-20251126-0064\",\"content_name\":\"치킨 가라아게\",\"content_img\":\"img\\/organized_ascii\\/japanese\\/MK-20251126-0064.png\",\"content_options\":\"치킨 가라아게\",\"content_amount\":1,\"content_price\":32400,\"total_price\":32400}]","Y","구매확정","35400","2025-11-27 11:41:12","0");
INSERT INTO pay VALUES("202511270342036239","","","","","","","","","","반정우입니다","[{\"content_code\":\"MK-20251126-0059\",\"content_name\":\"야끼찌꾸와\",\"content_img\":\"img\\/organized_ascii\\/japanese\\/MK-20251126-0059.png\",\"content_options\":\"야끼찌꾸와\",\"content_amount\":1,\"content_price\":25700,\"total_price\":25700}]","Y","구매확정","28700","2025-11-27 11:42:03","0");
INSERT INTO pay VALUES("202511270342566734","","","","","","","","","","bjw0619","[{\"content_code\":\"MK-20251126-0253\",\"content_name\":\"전라도 파김치\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0253.png\",\"content_options\":\"전라도 파김치\",\"content_amount\":1,\"content_price\":30000,\"total_price\":30000}]","Y","구매확정","33000","2025-11-27 11:42:56","0");
INSERT INTO pay VALUES("202511270345196238","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251126-0233\",\"content_name\":\"숙성 양념 쪽갈비\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0233.png\",\"content_options\":\"숙성 양념 쪽갈비\",\"content_amount\":1,\"content_price\":33000,\"total_price\":33000}]","Y","구매확정","36000","2025-11-27 11:45:19","0");
INSERT INTO pay VALUES("202511270347354794","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251126-0329\",\"content_name\":\"매운 크림 리조또\",\"content_img\":\"img\\/organized\\/양식\\/MK-20251126-0329.png\",\"content_options\":\"매운 크림 리조또\",\"content_amount\":1,\"content_price\":26000,\"total_price\":26000}]","Y","구매확정","29000","2025-11-27 11:47:36","0");
INSERT INTO pay VALUES("202511270348188620","","","","","","","","","","반정우입니다","[{\"content_code\":\"MK-20251126-0238\",\"content_name\":\"양평해장국\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0238.png\",\"content_options\":\"양평해장국\",\"content_amount\":1,\"content_price\":18000,\"total_price\":18000}]","Y","구매확정","21000","2025-11-27 11:48:18","0");
INSERT INTO pay VALUES("202512030529221652","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251126-0304\",\"content_name\":\"밀푀유나베\",\"content_img\":\"img\\/organized\\/일식\\/MK-20251126-0304.png\",\"content_options\":\"밀푀유나베\",\"content_amount\":1,\"content_price\":29000,\"total_price\":29000}]","N","결제완료","32000","2025-12-03 13:29:22","0");
INSERT INTO pay VALUES("202512040116361383","","","","","","","","","","반정우입니다.","[{\"content_code\":\"MK-20251203-0001\",\"content_name\":\"테스트\",\"content_img\":\"img\\/organized\\/기타\\/MK-20251203-0001.jpg\",\"content_options\":\"테스트\",\"content_amount\":1,\"content_price\":99999999,\"total_price\":99999999}]","Y","구매확정","99999999","2025-12-04 09:16:36","0");
INSERT INTO pay VALUES("202512040119268534","","","","","","","","","","반정우입니다.","[{\"content_code\":\"MK-20251126-0242\",\"content_name\":\"오골계삼계탕\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0242.png\",\"content_options\":\"오골계삼계탕\",\"content_amount\":99,\"content_price\":16000,\"total_price\":1584000}]","Y","구매확정","1584000","2025-12-04 09:19:26","0");
INSERT INTO pay VALUES("202512040121386254","","","","","","","","","","반정우입니다.","[{\"content_code\":\"MK-20251126-0335\",\"content_name\":\"빕스 클래식 스테이크\",\"content_img\":\"img\\/organized\\/양식\\/MK-20251126-0335.png\",\"content_options\":\"빕스 클래식 스테이크\",\"content_amount\":99,\"content_price\":11900,\"total_price\":1178100}]","Y","구매확정","1178100","2025-12-04 09:21:38","0");
INSERT INTO pay VALUES("202512040123512751","","","","","","","","","","반정우입니다.","[{\"content_code\":\"MK-20251126-0273\",\"content_name\":\"해물 부추 부침개\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0273.png\",\"content_options\":\"해물 부추 부침개\",\"content_amount\":1,\"content_price\":9900,\"total_price\":9900}]","Y","구매확정","12900","2025-12-04 09:23:51","0");
INSERT INTO pay VALUES("202512040126057108","","","","","","","","","","공준영입니다","[{\"content_code\":\"MK-20251126-0268\",\"content_name\":\"한우 곰국\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0268.png\",\"content_options\":\"한우 곰국\",\"content_amount\":1,\"content_price\":27000,\"total_price\":27000}]","Y","구매확정","30000","2025-12-04 09:26:05","0");
INSERT INTO pay VALUES("202512040126423116","","","","","","","","","","공준영입니다","[{\"content_code\":\"MK-20251126-0264\",\"content_name\":\"코다리조림\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0264.png\",\"content_options\":\"코다리조림\",\"content_amount\":1,\"content_price\":18000,\"total_price\":18000}]","Y","구매확정","21000","2025-12-04 09:26:42","0");
INSERT INTO pay VALUES("202512040141549001","","","","","","","","","","반정우입니다.","[{\"content_code\":\"MK-20251126-0353\",\"content_name\":\"닭가슴살 도시락\",\"content_img\":\"img\\/organized\\/건강식\\/MK-20251126-0353.png\",\"content_options\":\"닭가슴살 도시락\",\"content_amount\":1,\"content_price\":7000,\"total_price\":7000}]","Y","구매확정","10000","2025-12-04 09:41:54","0");
INSERT INTO pay VALUES("202512040148145682","","","","","","","","","","승준","[{\"content_code\":\"MK-20251126-0185\",\"content_name\":\"감자탕\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0185.png\",\"content_options\":\"감자탕\",\"content_amount\":1,\"content_price\":10900,\"total_price\":10900}]","Y","구매확정","13900","2025-12-04 09:48:14","0");
INSERT INTO pay VALUES("202512040150439890","","","","","","","","","","kong","[{\"content_code\":\"MK-20251126-0001\",\"content_name\":\"그릴드 닭가슴살 포케\",\"content_img\":\"img\\/organized_ascii\\/health\\/MK-20251126-0001.png\",\"content_options\":\"그릴드 닭가슴살 포케\",\"content_amount\":1,\"content_price\":7900,\"total_price\":7900}]","Y","구매확정","10900","2025-12-04 09:50:43","0");
INSERT INTO pay VALUES("202512040153363494","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251203-0001\",\"content_name\":\"테스트\",\"content_img\":\"img\\/organized\\/기타\\/MK-20251203-0001.jpg\",\"content_options\":\"테스트\",\"content_amount\":1,\"content_price\":99999999,\"total_price\":99999999}]","N","구매확정","99999999","2025-12-04 09:53:36","0");
INSERT INTO pay VALUES("202512040154193622","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251126-0304\",\"content_name\":\"밀푀유나베\",\"content_img\":\"img\\/organized\\/일식\\/MK-20251126-0304.png\",\"content_options\":\"밀푀유나베\",\"content_amount\":1,\"content_price\":29000,\"total_price\":29000}]","Y","구매확정","32000","2025-12-04 09:54:19","0");
INSERT INTO pay VALUES("202512040155131631","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251126-0348\",\"content_name\":\"해산물 토마토 파스타\",\"content_img\":\"img\\/organized\\/양식\\/MK-20251126-0348.png\",\"content_options\":\"해산물 토마토 파스타\",\"content_amount\":1,\"content_price\":14000,\"total_price\":14000}]","Y","구매확정","17000","2025-12-04 09:55:13","0");
INSERT INTO pay VALUES("202512040156497762","","","","","","","","","","서아","[{\"content_code\":\"MK-20251126-0349\",\"content_name\":\"화이트 라구 파스타\",\"content_img\":\"img\\/organized\\/양식\\/MK-20251126-0349.png\",\"content_options\":\"화이트 라구 파스타\",\"content_amount\":1,\"content_price\":26000,\"total_price\":26000}]","Y","구매확정","29000","2025-12-04 09:56:49","0");
INSERT INTO pay VALUES("202512040159085482","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251126-0242\",\"content_name\":\"오골계삼계탕\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0242.png\",\"content_options\":\"오골계삼계탕\",\"content_amount\":1,\"content_price\":16000,\"total_price\":16000}]","N","취소신청","19000","2025-12-04 09:59:08","0");
INSERT INTO pay VALUES("202512040214311177","","","","","","","","","","kong","[{\"content_code\":\"MK-20251126-0242\",\"content_name\":\"오골계삼계탕\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0242.png\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":16000,\"total_price\":16000}]","Y","반품신청","19000","2025-12-04 10:14:31","0");
INSERT INTO pay VALUES("202512040214399243","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251126-0294\",\"content_name\":\"티앤미미 우삼겹차우면\",\"content_img\":\"img\\/organized\\/중식\\/MK-20251126-0294.png\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":20900,\"total_price\":20900}]","Y","구매확정","23900","2025-12-04 10:14:39","0");
INSERT INTO pay VALUES("202512040218429561","","","","","","","","","","현곤","[{\"content_code\":\"MK-20251126-0261\",\"content_name\":\"차돌떡볶이\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0261.png\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":19000,\"total_price\":19000}]","Y","구매확정","22000","2025-12-04 10:18:42","0");
INSERT INTO pay VALUES("202512040225309197","","","","","","","","","","공준영입니다","[{\"content_code\":\"MK-20251126-0356\",\"content_name\":\"보양식 해신탕\",\"content_img\":\"img\\/organized\\/건강식\\/MK-20251126-0356.png\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":11900,\"total_price\":11900}]","Y","구매확정","14900","2025-12-04 10:25:30","0");
INSERT INTO pay VALUES("202512040231536461","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251204-0001\",\"content_name\":\"이현곤표 제육볶음\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0001.webp\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":7800000,\"total_price\":7800000}]","Y","반품신청","7800000","2025-12-04 10:31:53","0");
INSERT INTO pay VALUES("202512040234244867","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251204-0003\",\"content_name\":\"이현곤표 김치 볶음밥\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0003.jfif\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":5200000,\"total_price\":5200000}]","Y","구매확정","5200000","2025-12-04 10:34:24","0");
INSERT INTO pay VALUES("202512040237148546","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251204-0005\",\"content_name\":\"이현곤표 김치찌개\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0005.jpg\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":8600000,\"total_price\":8600000}]","Y","구매확정","8600000","2025-12-04 10:37:14","0");
INSERT INTO pay VALUES("202512040239131209","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251204-0006\",\"content_name\":\"이현곤표 된장찌개\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0006.jpg\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":8700000,\"total_price\":8700000}]","Y","구매확정","8700000","2025-12-04 10:39:13","0");
INSERT INTO pay VALUES("202512040241466315","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251204-0008\",\"content_name\":\"이현곤표 라면\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0008.jpg\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":37000000,\"total_price\":37000000}]","Y","구매확정","37000000","2025-12-04 10:41:46","0");
INSERT INTO pay VALUES("202512040242446909","","","","","","","","","","공준영입니다","[{\"content_code\":\"MK-20251204-0008\",\"content_name\":\"이현곤표 라면\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0008.jpg\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":37000000,\"total_price\":37000000}]","Y","구매확정","37000000","2025-12-04 10:42:44","0");
INSERT INTO pay VALUES("202512040243284719","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251204-0007\",\"content_name\":\"이현곤표 트러플 파스타\",\"content_img\":\"img\\/organized\\/양식\\/MK-20251204-0007.png\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":12600000,\"total_price\":12600000}]","Y","구매확정","12600000","2025-12-04 10:43:28","0");
INSERT INTO pay VALUES("202512040244025729","","","","","","","","","","공준영입니다","[{\"content_code\":\"MK-20251204-0006\",\"content_name\":\"이현곤표 된장찌개\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0006.jpg\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":8700000,\"total_price\":8700000}]","Y","구매확정","8700000","2025-12-04 10:44:02","0");
INSERT INTO pay VALUES("202512040245371193","","","","","","","","","","ㅇ현곤","[{\"content_code\":\"MK-20251204-0001\",\"content_name\":\"이현곤표 제육볶음\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0001.webp\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":7800000,\"total_price\":7800000}]","Y","구매확정","7800000","2025-12-04 10:45:37","0");
INSERT INTO pay VALUES("202512040245406089","","","","","","","","","","반정우입니다.","[{\"content_code\":\"MK-20251204-0007\",\"content_name\":\"이현곤표 트러플 파스타\",\"content_img\":\"img\\/organized\\/양식\\/MK-20251204-0007.png\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":12600000,\"total_price\":12600000}]","Y","구매확정","12600000","2025-12-04 10:45:40","0");
INSERT INTO pay VALUES("202512040247022688","","","","","","","","","","반정우입니다.","[{\"content_code\":\"MK-20251204-0001\",\"content_name\":\"이현곤표 제육볶음\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0001.webp\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":7800000,\"total_price\":7800000}]","Y","구매확정","7800000","2025-12-04 10:47:02","0");
INSERT INTO pay VALUES("202512040251562602","","","","","","","","","","현곤","[{\"content_code\":\"MK-20251204-0002\",\"content_name\":\"카리나\",\"content_img\":\"img\\/organized\\/건강식\\/MK-20251204-0002.jpg\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":2147483647,\"total_price\":2147483647}]","Y","구매확정","2147483647","2025-12-04 10:51:56","0");
INSERT INTO pay VALUES("202512040253533072","","","","","","","","","","현곤","[{\"content_code\":\"MK-20251204-0003\",\"content_name\":\"이현곤표 김치 볶음밥\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0003.jfif\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":5200000,\"total_price\":5200000}]","Y","구매확정","5200000","2025-12-04 10:53:53","0");
INSERT INTO pay VALUES("202512060953564704","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251126-0146\",\"content_name\":\"양념 닭목살 볶음\",\"content_img\":\"img\\/organized_ascii\\/korean\\/MK-20251126-0146.png\",\"content_options\":\"양념 닭목살 볶음\",\"content_amount\":1,\"content_price\":15000,\"total_price\":15000}]","N","결제완료","18000","2025-12-06 17:53:56","0");
INSERT INTO pay VALUES("202512061443053913","","","","","","","","","","현곤","[{\"content_code\":\"MK-20251204-0011\",\"content_name\":\"이현곤표 소불고기\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0011.jpg\",\"content_options\":\"기본\",\"content_amount\":1,\"content_price\":9979000,\"total_price\":9979000}]","Y","구매확정","9979000","2025-12-06 22:43:05","0");
INSERT INTO pay VALUES("202512081046564850","","","","","","","","","","dnf826","[{\"content_code\":\"MK-20251204-0011\",\"content_name\":\"이현곤표 소불고기\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251204-0011.jpg\",\"content_options\":\"이현곤표 소불고기\",\"content_amount\":2,\"content_price\":9979000,\"total_price\":19958000},{\"content_code\":\"MK-20251126-0242\",\"content_name\":\"오골계삼계탕\",\"content_img\":\"img\\/organized\\/한식\\/MK-20251126-0242.png\",\"content_options\":\"오골계삼계탕\",\"content_amount\":2,\"content_price\":16000,\"total_price\":32000}]","N","결제완료","19990000","2025-12-08 18:46:56","0");



DROP TABLE IF EXISTS point_history;

CREATE TABLE `point_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  `type` varchar(20) NOT NULL COMMENT 'attendance, ad, purchase, use, etc',
  `description` varchar(255) NOT NULL,
  `reg_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `reg_date` (`reg_date`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO point_history VALUES("1","dnf826","10","attendance","일일 출석체크","2025-12-06 21:04:55");
INSERT INTO point_history VALUES("2","dnf826","10","ad","광고 시청 보상","2025-12-06 21:05:01");
INSERT INTO point_history VALUES("3","dnf826","10","ad","광고 시청 보상","2025-12-06 21:05:02");
INSERT INTO point_history VALUES("4","현곤","100","purchase","구매 확정 보상","2025-12-06 22:43:12");
INSERT INTO point_history VALUES("5","dnf826","10","attendance","일일 출석체크","2025-12-08 18:08:57");
INSERT INTO point_history VALUES("6","dnf826","100","purchase","구매 확정 보상","2025-12-08 18:52:04");
INSERT INTO point_history VALUES("7","kong","10","ad","광고 시청 보상","2025-12-11 10:47:29");



DROP TABLE IF EXISTS review;

CREATE TABLE `review` (
  `review_id` char(15) NOT NULL,
  `writer_id` char(15) NOT NULL,
  `content_code` varchar(50) NOT NULL,
  `review_contents` varchar(500) NOT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `star` int(1) DEFAULT NULL,
  `review_regdate` datetime DEFAULT current_timestamp(),
  `views` int(11) DEFAULT 0,
  PRIMARY KEY (`review_id`),
  KEY `idx_review_content_code` (`content_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO review VALUES("20221201_55a549","song","221124-010","이정도 가격에 퀄리티 짱이네요","img/reviews/8.jpeg","4","2025-11-27 09:46:57","0");
INSERT INTO review VALUES("20221201_9fb479","song","221124-010","상품 너무 좋네요","img/reviews/8.jpeg","5","2025-11-27 09:46:57","0");
INSERT INTO review VALUES("20221201_a5ddc2","song","221124-001","sdfdf","img/reviews/14.png","4","2025-11-27 09:46:57","0");
INSERT INTO review VALUES("20221201_eaed6f","song","221124-010","dkjkldsf","img/reviews/1.jpeg","4","2025-11-27 09:46:57","0");
INSERT INTO review VALUES("20221208_49a95d","song","221119-004","너무 예뻐 최고","img/reviews/2.jpeg","4","2025-11-27 09:46:57","0");
INSERT INTO review VALUES("20251127_0efaed","반정우입니다","MK-20251126-0238","신구대 다닐 때 많이 먹었는데 기억이 나네요 ㅎㅎ 옛날 기억 난 김에 3발 뻇습니다 ","","5","2025-11-27 11:48:53","9");
INSERT INTO review VALUES("20251127_10297f","ksh03029","MK-20251126-0202","이현곤 꼬리족발 맛이 좀 별로예요.. 뭔가 좀 질기다","","1","2025-11-27 11:26:45","1");
INSERT INTO review VALUES("20251127_1b6935","sexyguy","MK-20251126-0366","팥으로 시키려 했는데, 실수로 슈크림 주문했어요. 교환해주세요.","","2","2025-11-27 11:39:59","0");
INSERT INTO review VALUES("20251127_22b02a","현곤","MK-20251126-0365","건어물 5종 시켯는데 반정우가 왔네요 잘 받았습니다~ 롤 시키니까 불평도 없이 잘 하네요","","5","2025-11-27 11:24:17","0");
INSERT INTO review VALUES("20251127_24aed9","ksh03029","MK-20251126-0100","게가 아니라 개가 왔네요 비위 상해서 못먹었어요","","1","2025-11-27 11:36:53","1");
INSERT INTO review VALUES("20251127_388acf","반정우입니다","MK-20251126-0064","가라오케 아니였어요?","","1","2025-11-27 11:41:32","3");
INSERT INTO review VALUES("20251127_3aeea5","sexyguy","MK-20251126-0185","머리카락 들어있던데, 사장님 여자신가요","","5","2025-11-27 11:38:52","0");
INSERT INTO review VALUES("20251127_4c82f2","현곤","MK-20251126-0265","준영이가 맛있고 콘치즈가 착해요","","1","2025-11-27 11:14:53","0");
INSERT INTO review VALUES("20251127_658b9a","dnf826","MK-20251126-0240","나김우림인데 이현곤 맛있더라~~","","5","2025-11-27 11:11:55","0");
INSERT INTO review VALUES("20251127_75372c","sexyguy","MK-20251126-0349","음식 위에 벌레가 들어있어요.\n단백질 서비스 감사합니다.","","4","2025-11-27 11:37:56","0");
INSERT INTO review VALUES("20251127_80e748","반정우입니다","MK-20251126-0217","아 젖이 아니라 젓이였네요... 헷갈렷습니다 이미 뜯었는데 반품 될까요?","","1","2025-11-27 11:33:37","2");
INSERT INTO review VALUES("20251127_8947fa","bjw0619","MK-20251126-0253","국내산인줄 알았더니 해외에서 오는 거였네요 환불 가능할까요?","","1","2025-11-27 11:43:22","2");
INSERT INTO review VALUES("20251127_906e10","kong","MK-20251126-0235","현곤이찜인줄 알았는데 현곤이의 소중한 알이 왔네요 환불은 안된다고해서 일단 먹어볼게요","","1","2025-11-27 11:30:36","0");
INSERT INTO review VALUES("20251127_95c624","ㅇ현곤","MK-20251126-0233","맛은 있네요 그리고 남은 뼈는 다른 용도로 제가 잘 썼습니다 ㅎㅎ","","4","2025-11-27 11:45:55","2");
INSERT INTO review VALUES("20251127_a705c2","반정우입니다","MK-20251126-0316","좋습니다 이거 먹고 못 참아서 3발 연속으로 뺏네요","","5","2025-11-27 11:38:29","2");
INSERT INTO review VALUES("20251127_b9e7ed","반정우입니다","MK-20251126-0059","잘 썻습니다 ㅎㅎ 다른 용도로요 ","","1","2025-11-27 11:42:23","0");
INSERT INTO review VALUES("20251127_c354a0","반정우입니다","MK-20251126-0361","아 또 제가 원하던 가슴이 아니네요 그래도 잘 받았습니다","","1","2025-11-27 11:28:24","2");
INSERT INTO review VALUES("20251127_c396ca","ㅇ현곤","MK-20251126-0329","누가 토한 것 같은 비주얼...좀 실망이네요 앞으론 이용 안할거 같아요","","1","2025-11-27 11:48:08","3");
INSERT INTO review VALUES("20251127_c7aa6b","dnf826","MK-20251126-01","맛있어요!!!!굿굿굿","","2","2025-11-27 09:46:57","0");
INSERT INTO review VALUES("20251127_cc17e1","ksh03029","MK-20251126-0080","제 친구 현곤이가 되게 좋아할거 같은 맛이네요 !!  워낙 에겐스러워서 마라탕 좋아하거든요","","3","2025-11-27 11:28:28","3");
INSERT INTO review VALUES("20251127_cdd8f7","kong","MK-20251126-0185","이현곤은 문제다 진짜","","4","2025-11-27 11:24:17","0");
INSERT INTO review VALUES("20251127_d26049","반정우입니다","MK-20251126-0252","국내산이 아니여서 그런가 좀 그렇네요 다음부턴 안 먹을 거 같아요","","1","2025-11-27 11:31:20","2");
INSERT INTO review VALUES("20251127_d4b51d","ldd72","MK-20251126-0265","치즈가 부드럽고 콘이 달달해요","","4","2025-11-27 11:33:30","2");
INSERT INTO review VALUES("20251127_de0f76","dnf826","MK-20251126-02","나김우림인데 이현곤 맛있더라~~","","5","2025-11-27 11:09:35","0");
INSERT INTO review VALUES("20251127_f4ebc9","반정우입니다","MK-20251126-0363","아 그 전복이 아니네요 ? 제가 원하던 전복은 아니지만 잘 받았습니다","","1","2025-11-27 11:27:42","1");
INSERT INTO review VALUES("20251127_f90196","kong","MK-20251126-0301","김우림 크리미해서 좋아요","","5","2025-11-27 11:27:37","1");
INSERT INTO review VALUES("20251204_02e040","반정우입니다.","MK-20251204-0001","너무 맛있어서 좋아요!! 저희 집 강아지도 좋아해요","","5","2025-12-04 10:47:30","2");
INSERT INTO review VALUES("20251204_0af2c3","승준","MK-20251126-0185","맛있어요 가게에 가서 먹는 거랑 차이가 없어요 ","","5","2025-12-04 09:48:38","0");
INSERT INTO review VALUES("20251204_1a322f","반정우입니다.","MK-20251126-0335","맛있어요 좋네요! 저희 아이들도 좋아해요 다음에 또 구매할 거 같아요 ","","5","2025-12-04 09:23:28","2");
INSERT INTO review VALUES("20251204_1bbeb3","현곤","MK-20251204-0002","당장 글 내리세요 안 그러면 제 여자친구 소속사 통해서 고소 하겠습니다 지금 제 여자친구가 몹시 기분 나빠 하고 있습니다 이건 경고입니다 ","","1","2025-12-04 10:52:53","7");
INSERT INTO review VALUES("20251204_3419d5","ㅇ현곤","MK-20251204-0003","무슨 이런 게 김치볶음밥이라고..ㅋㅋㅋ  김치도 짜잘하게 들어있고 진짜 말 그대로 김치만 들어가있는 김치볶음밥일줄은 상상도 못했습니다. 520만원 주고 먹은 김치볶음밥이라고 기대 많이 했는데 그냥 고소하겠습니닫","","1","2025-12-04 10:36:43","3");
INSERT INTO review VALUES("20251204_3c0b61","공준영입니다","MK-20251126-0356","학교 점심에 먹기 간편해서 좋아요 친구들이 부럽다고 쳐다봐요 ㅋㅋ","","5","2025-12-04 10:26:03","1");
INSERT INTO review VALUES("20251204_40f09c","반정우입니다.","MK-20251126-0273","기대 했는데 아쉽네요...","","1","2025-12-04 09:24:24","2");
INSERT INTO review VALUES("20251204_493b2a","반정우입니다.","MK-20251204-0007","여자친구도 좋아하고 저도 맛있었어요 ","","5","2025-12-04 10:46:04","3");
INSERT INTO review VALUES("20251204_4e1753","dnf826","MK-20251204-0001","가격에 비해 너무 맛있어요","","4","2025-12-04 10:32:12","3");
INSERT INTO review VALUES("20251204_671007","공준영입니다","MK-20251126-0264","칵 퉤 이걸 누구 먹으라고 만든 건지;; 너무 비리고 질겨서 다신 안 먹을 거 같네요 이거 먹고 변기에 머리 30째 박고 있습니다;;","","1","2025-12-04 09:27:39","11");
INSERT INTO review VALUES("20251204_70d84c","ㅇ현곤","MK-20251204-0005","제품이 사진 그대로 올 줄 알았는데 김치만 있는 김치찌개라니 ㅋㅋㅋㅋ 이 가격 주고 먹을 음식은 아닌 것 같은데요? 다른 분들은 사진에 속지 마세요 맛도 드럽게 없고 그냥 물에 김치만 넣고 끓인 셈입니다. 맛도 밍밍해서 먹다가 바로 음쓰통 직행이였습니다","","1","2025-12-04 10:38:56","6");
INSERT INTO review VALUES("20251204_76024e","공준영입니다","MK-20251126-0268","바쁠 때 먹기 간편해서 좋아요!","","5","2025-12-04 09:26:29","2");
INSERT INTO review VALUES("20251204_7633e2","ㅇ현곤","MK-20251126-0294","차우차우가 들어간 면인가요? ","","1","2025-12-04 10:15:15","0");
INSERT INTO review VALUES("20251204_77791c","공준영입니다","MK-20251204-0006","맛있어요 저희 가족들도 다 좋아해요 또 시켜서 먹을 거 같아요 ","","5","2025-12-04 10:44:34","2");
INSERT INTO review VALUES("20251204_7954f1","kong","MK-20251126-0242","현곤이가 화장실에서 먹기 편하다네요","","5","2025-12-04 10:15:40","1");
INSERT INTO review VALUES("20251204_81ee28","반정우입니다.","MK-20251126-0353","너무 좋아요 바쁠 때 애기 밥 챙기는 게 힘들었는데  너무 좋아요\n2살 된 아들이 이거 하나 돌려주면 좋아해요 ","","5","2025-12-04 09:43:48","1");
INSERT INTO review VALUES("20251204_976ae8","반정우입니다.","MK-20251126-0242","미국에서 할렘가에서 왔나봐요 ~ 까맣네요","","5","2025-12-04 09:20:10","3");
INSERT INTO review VALUES("20251204_a4694d","kong","MK-20251126-0001","닭가슴 생각보다 맛있네요","","4","2025-12-04 09:51:33","0");
INSERT INTO review VALUES("20251204_be2268","ㅇ현곤","MK-20251126-0348","토마토 알레르기 있어서 빼고 달라 했는데 제품 그대로 줬어요 환불 부탁드립니다.","","1","2025-12-04 09:56:18","1");
INSERT INTO review VALUES("20251204_bf1ab0","ㅇ현곤","MK-20251204-0001","ㅋㅋㅋㅋ현곤 게이야 제육이나 볶아온나. ","","2","2025-12-04 10:47:38","1");
INSERT INTO review VALUES("20251204_c071c5","현곤","MK-20251126-0261","맛있어요 좋아요 !!!!!!!","","5","2025-12-04 10:19:26","2");
INSERT INTO review VALUES("20251204_c5c017","ㅇ현곤","MK-20251126-0304","그냥 그럭저럭이였습니다","","2","2025-12-04 09:54:49","0");
INSERT INTO review VALUES("20251204_ccc5e9","서아","MK-20251126-0349","맛있고 간편해서 좋아요","","5","2025-12-04 09:57:07","0");
INSERT INTO review VALUES("20251204_cfc1d0","현곤","MK-20251204-0003","어렷을 때 어머니가 해주시던 그 맛입니다...ㅜㅜ","","5","2025-12-04 10:54:20","3");
INSERT INTO review VALUES("20251204_d13d9b","공준영입니다","MK-20251204-0008","맛있어요 너무 좋아요!! 벌써 10번 넘게 주문했어요","","5","2025-12-04 10:43:13","1");
INSERT INTO review VALUES("20251204_e487be","반정우입니다.","MK-20251203-0001","시발 ㄹㅈㄷ 너무 좋아요~","","5","2025-12-04 09:17:02","7");
INSERT INTO review VALUES("20251204_ea01f6","ㅇ현곤","MK-20251204-0006","그냥 여기 회사는 믿고 거르는 게 맞을듯. 된장 자체가 맛이 없으니 된장찌개도 맛이 없죠. 입맛 버렸네요\n다음날에 식중독 걸렸어요 고소할게요 그냥 몇 백만원 짜리 된찌라길래 이번에야말로 기대했더니...","","1","2025-12-04 10:41:18","3");
INSERT INTO review VALUES("20251206_e05393","현곤","MK-20251204-0011","저희 집 강아지도 맛있다고 먹네요","","5","2025-12-06 22:43:29","1");



DROP TABLE IF EXISTS review_comments;

CREATE TABLE `review_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `review_id` varchar(50) NOT NULL,
  `writer_id` varchar(50) NOT NULL,
  `comment_content` text NOT NULL,
  `reg_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`comment_id`),
  KEY `review_id` (`review_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO review_comments VALUES("1","20251127_0efaed","dnf826","테스트에요","2025-12-03 14:25:53");
INSERT INTO review_comments VALUES("2","20251127_80e748","ㅇ현곤","현곤아 사칭하고 다니지마라","2025-12-04 09:31:00");
INSERT INTO review_comments VALUES("3","20251204_671007","ㅇ현곤","저도 그랬어요 이건 다신 안 먹을 거 같네요","2025-12-04 09:32:20");
INSERT INTO review_comments VALUES("4","20251204_76024e","ㅇ현곤","바쁘면 삼각김밥이나 먹으세요","2025-12-04 09:32:43");
INSERT INTO review_comments VALUES("5","20251204_40f09c","ㅇ현곤","밀키트 따위를 뭐 기대하고 먹어?","2025-12-04 09:33:01");
INSERT INTO review_comments VALUES("6","20251204_1a322f","ㅇ현곤","엥 저희 아이들은 맛 없다는데 님 아이들은 싸구려 입맛인가봐요","2025-12-04 09:35:49");
INSERT INTO review_comments VALUES("7","20251204_671007","반정우입니다.","ㅋㅋ다들 입맛이 같네요 저도 그렇습니다 다신 안 먹어요~","2025-12-04 09:36:33");
INSERT INTO review_comments VALUES("8","20251204_671007","반정우입니다.","ㅋㅋ다들 입맛이 같네요 저도 그렇습니다 다신 안 먹어요~","2025-12-04 09:36:33");
INSERT INTO review_comments VALUES("9","20251204_671007","반정우입니다.","ㅋㅋ다들 입맛이 같네요 저도 그렇습니다 다신 안 먹어요~","2025-12-04 09:36:33");
INSERT INTO review_comments VALUES("10","20251204_671007","반정우입니다.","ㅋㅋ다들 입맛이 같네요 저도 그렇습니다 다신 안 먹어요~","2025-12-04 09:36:33");
INSERT INTO review_comments VALUES("11","20251204_671007","반정우입니다.","ㅋㅋ다들 입맛이 같네요 저도 그렇습니다 다신 안 먹어요~","2025-12-04 09:36:33");
INSERT INTO review_comments VALUES("12","20251204_671007","반정우입니다.","ㅋㅋ다들 입맛이 같네요 저도 그렇습니다 다신 안 먹어요~","2025-12-04 09:36:33");
INSERT INTO review_comments VALUES("13","20251204_671007","반정우입니다.","ㅋㅋ다들 입맛이 같네요 저도 그렇습니다 다신 안 먹어요~","2025-12-04 09:36:33");
INSERT INTO review_comments VALUES("14","20251127_cc17e1","ㅇ현곤","마라탕 돈 주고 공짜로 먹으라 해도 안 먹어 왜 이딴 걸 먹어","2025-12-04 09:38:24");
INSERT INTO review_comments VALUES("15","20251127_d26049","ㅇ현곤","518은 민주화인가요 폭동인가요","2025-12-04 09:39:10");
INSERT INTO review_comments VALUES("16","20251127_d4b51d","ㅇ현곤","이건 ㅇㅈ이요~","2025-12-04 09:39:25");
INSERT INTO review_comments VALUES("17","20251127_c354a0","ㅇ현곤","제 가슴이 더 큰 거 같아요","2025-12-04 09:40:37");
INSERT INTO review_comments VALUES("18","20251204_e487be","ㅇ현곤","아 ㅈㄴ 귀여운 척 하고 카리나보다 못생긴게 왜 나대냐","2025-12-04 09:41:12");
INSERT INTO review_comments VALUES("19","20251204_e487be","반정우입니다.","꼴리네","2025-12-04 09:41:33");
INSERT INTO review_comments VALUES("20","20251127_0efaed","ㅇ현곤","아아 그때 생각이 나긴 하네요 현곤이가 먹었다가 무발기사정 했던 기억이 납니다","2025-12-04 09:42:04");
INSERT INTO review_comments VALUES("21","20251127_388acf","ㅇ현곤","가라오케는 제 전문인데 추천해드릴까요?","2025-12-04 09:42:45");
INSERT INTO review_comments VALUES("22","20251204_976ae8","ㅇ현곤","으..보기만 해도 토 쏠려","2025-12-04 09:43:40");
INSERT INTO review_comments VALUES("23","20251204_e487be","ㅇ현곤","윗 댓글 ---. 이현곤 본인","2025-12-04 09:44:13");
INSERT INTO review_comments VALUES("24","20251204_e487be","ㅇ현곤","사진에 있는 여자 인스타 @III_IIIiIII_II","2025-12-04 09:45:58");
INSERT INTO review_comments VALUES("25","20251127_a705c2","ㅇ현곤","현곤아 내 이름 사칭하고 다니지마","2025-12-04 09:47:11");
INSERT INTO review_comments VALUES("26","20251204_81ee28","ㅇ현곤","2살 된 아들을 이런 쓰레기를 먹이다니 부모로써 진짜 못났네요","2025-12-04 09:48:27");
INSERT INTO review_comments VALUES("27","20251204_70d84c","반정우입니다.","ㅋㅋ무지성 악플 죽어라","2025-12-04 10:40:57");
INSERT INTO review_comments VALUES("28","20251204_3419d5","반정우입니다.","전 맛있어서 아랫도리가 서기까지 햇는데 ㅋㅋ","2025-12-04 10:41:16");
INSERT INTO review_comments VALUES("29","20251204_ea01f6","반정우입니다.","무지성 악플 멈춰주세요 여기 회사 제품은 저희 집 강아지도 잘 먹습니다","2025-12-04 10:41:52");
INSERT INTO review_comments VALUES("30","20251204_3419d5","ㅇ현곤","현곤아 너 발기부전이잖아","2025-12-04 10:48:17");
INSERT INTO review_comments VALUES("31","20251204_493b2a","ㅇ현곤","ㅋㅋㅋㅋㅋㅋㅋㅋㅋㅋ끼리끼리 잘 만나네요","2025-12-04 10:49:06");
INSERT INTO review_comments VALUES("32","20251204_02e040","ㅇ현곤","강아지도 잘 먹는다길래 저희 집 고양이도 줬더니 잘 먹네요 ㅎㅎ 생긴 게 개밥이라 그런가 반려동물들도 꺼려하지 않고 바로바로 먹더라구용 솔직한 리뷰 감사합니다 덕분에 시켜서 잘 먹었어요","2025-12-04 10:50:08");
INSERT INTO review_comments VALUES("33","20251204_77791c","ㅇ현곤","입맛이 아주 싸구려시네요 제가 먹었을땐 바로 음쓰통 직행이였는데","2025-12-04 10:50:55");
INSERT INTO review_comments VALUES("34","20251204_d13d9b","ㅇ현곤","차라리 라면 5개입 봉지 사와서 직접 끓여먹지 애초에 안에 구성부터가 씹창렬임","2025-12-04 10:51:17");
INSERT INTO review_comments VALUES("35","20251204_3c0b61","ㅇ현곤","부럽다고 쳐다보는게 아니라 안쓰러워서 쳐다보는 거 같은데요","2025-12-04 11:17:07");
INSERT INTO review_comments VALUES("36","20251204_4e1753","ㅇ현곤","별로요","2025-12-04 11:17:18");
INSERT INTO review_comments VALUES("37","20251204_7954f1","ㅇ현곤","삼계탕이 어떻게 화장실에서 먹기 편해요","2025-12-04 11:17:51");
INSERT INTO review_comments VALUES("38","20251206_e05393","dnf826","ㅋㅋㅋㅋㅋ","2025-12-06 23:49:23");



DROP TABLE IF EXISTS wishlist;

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` varchar(64) NOT NULL,
  `content_code` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_member_code` (`member_id`,`content_code`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO wishlist VALUES("30","dnf826","MK-20251126-0366","2025-11-27 09:28:15");
INSERT INTO wishlist VALUES("32","dnf826","221124-009","2025-11-27 09:29:34");
INSERT INTO wishlist VALUES("34","dnf826","MK-20251126-0281","2025-11-27 10:13:39");
INSERT INTO wishlist VALUES("37","dnf826","MK-20251126-0273","2025-11-27 10:31:04");
INSERT INTO wishlist VALUES("38","dnf826","MK-20251126-0185","2025-11-27 10:46:30");



