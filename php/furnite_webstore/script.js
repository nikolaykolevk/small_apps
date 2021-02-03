var result = {};

//Example Data for the website
result.newItemsDescription = "promocionalno opisanie";
result.newItemsHeader = "drugo zaglavie";

result.newItems = [];

result.newItems[0] = {};
result.newItems[0].imageUrl = "tmpImage1.jpg";
result.newItems[0].name = "leglo1";
result.newItems[0].price = 99.99;
result.newItems[0].id = "1";

result.newItems[1] = {};
result.newItems[1].imageUrl = "tmpImage2.jpg";
result.newItems[1].name = "leglo2";
result.newItems[1].price = 109.99;
result.newItems[1].id = "2";

result.promotionItemsDescription = "opisanie";
result.promotionItemsHeader = "novo zaglavie";

result.promotionItems = [];

result.promotionItems[0] = {};
result.promotionItems[0].imageUrl = "tmpImage5.jpg";
result.promotionItems[0].name = "divan1";
result.promotionItems[0].price = 199.99;
result.promotionItems[0].id = "3";

result.promotionItems[1] = {};
result.promotionItems[1].imageUrl = "tmpImage7.jpeg";
result.promotionItems[1].name = "divan2";
result.promotionItems[1].price = 299.99;
result.promotionItems[1].id = "4";

result.login = 1;
result.loginName = "Nikolay Kolev";
result.cartProducts = [];

//imageSource name size colour quantity price

result.cartProducts[0] = {
		imageSource : "tmpImage1.jpg",
		name : "Голямо Легло",
		size : "190 x 120",
		quantity : 2,
		price: "30.40",
		id: 0
};

result.cartProducts[1] = {
		imageSource : "tmpImage2.jpg",
		name : "Средно Легло",
		size : "170 x 100",
		quantity : 5,
		price: "70.70",
		id: 1
};


result.categories = [];
result.categories[0]={};
result.categories[0].name="Матраци";
result.categories[0].link="/legla/category.php?q=Матраци";
result.categories[1]={};
result.categories[1].name="Легла";
result.categories[1].link="/legla/category.php?q=Легла";
result.categories[2]={};
result.categories[2].name="Възглавници";
result.categories[2].link="/legla/category.php?q=Възглавници";

result.facebookLink = ""
result.phoneNumber = "0888777665";
result.copyright = "kolev";
result.copyrightLink = "/legla";
result.websiteInfo = "Още наистина много текст, който ше четеш i tova i tovaasd asd samd asldm kalsmd lkmaslkd askdl ";

result.products = [];
result.products[0] = {
		mainImg: "tmpImage1.jpg",
		name: "leglo1",
		id: "1",
		description: "mnogo opisatelen tekst",
		images: ["tmpImage2.jpg", "tmpImage3.jpg"],
		models: [{
			price : 100.00,
			size : "M"
		},{
			price : 110.00,
			size : "L"
		},{
			price : 130.00,
			size : "XL"
		}]
		
};
result.products[1] = {
		mainImg: "tmpImage2.jpg",
		name: "leglo2",
		id: "2",
		description: "mnogo opisatelen tekst2",
		images: ["tmpImage1.jpg", "tmpImage3.jpg"],
		models: [{
			price : 105.00,
			size : "XXS"
		},{
			price : 115.00,
			size : "xS"
		},{
			price : 135.00,
			size : "XXS"
		}]
};
result.products[2] = {
		mainImg: "tmpImage1.jpg",
		name: "leglo1",
		id: "3",
		description: "mnogo opisatelen tekst",
		images: ["tmpImage2.jpg", "tmpImage3.jpg"],
		models: [{
			price : 100.00,
			size : "M"
		},{
			price : 110.00,
			size : "L"
		},{
			price : 130.00,
			size : "XL"
		}]
		
};

