-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 19, 2024 at 03:14 PM
-- Server version: 10.11.8-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chasingscents_db`
--
CREATE DATABASE IF NOT EXISTS `chasingscents_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `chasingscents_db`;

-- --------------------------------------------------------

--
-- Table structure for table `billing_details`
--

DROP TABLE IF EXISTS `billing_details`;
CREATE TABLE `billing_details` (
  `billing_id` varchar(11) NOT NULL,
  `pay_id` varchar(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `save_address` varchar(255) NOT NULL,
  `order_note` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `prod_no` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderdetail`
--

DROP TABLE IF EXISTS `orderdetail`;
CREATE TABLE `orderdetail` (
  `order_id` varchar(15) NOT NULL,
  `prod_no` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_each` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `order_id` varchar(15) NOT NULL,
  `userid` int(11) NOT NULL,
  `order_datetime` datetime NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `pay_id` varchar(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` varchar(150) NOT NULL,
  `order_id` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `prod_no` int(11) NOT NULL,
  `prodname` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `ml` int(11) NOT NULL DEFAULT 100,
  `gender` varchar(100) NOT NULL,
  `image` text NOT NULL DEFAULT 'default.jpg',
  `status` varchar(50) NOT NULL DEFAULT 'Available',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`prod_no`, `prodname`, `description`, `type`, `price`, `quantity`, `ml`, `gender`, `image`, `status`, `created_at`) VALUES
(4, 'Happy Heart', 'The top notes of Clinique Happy Heart perfume include mandarin orange and cucumber, giving it a crisp and refreshing scent. The heart notes are a blend of carrot and water hyacinth, which create a floral and slightly earthy aroma.', 'Fresh', 3300.00, 50, 100, 'Female', 'HappyHeart.jpg', 'Available', '2024-05-05 00:33:38'),
(5, 'Acqua di Gioia', 'Acqua di Gioia represents the joy of the Mediterranean Sea. This refreshing aquatic fragrance opens with a beautiful blend of jasmine and zesty lemon, warmed with woody cedar at the base. Inspired by Italy\'s Mediterranean coast, this fresh perfume is a singular blend of serenity and exhilaration.', 'Fresh', 4200.00, 45, 100, 'Female', 'Acqua di Gioia.jpg', 'Available', '2024-05-05 00:40:10'),
(6, 'Black Opium', 'The signature black coffee accord is paired with sensual vanilla, enriched by the softness of white flowers and orange blossom, set against a base of patchouli and comforting white musk. A daring contrast of light and dark, for a women\'s fragrance that bewitches with its trail.', 'Vanilla', 2850.00, 50, 100, 'Female', 'Yves-Saint-Laurent-Black-Opium-EDP-90ml.jpg', 'Available', '2024-05-05 00:48:16'),
(7, 'Versace Man Eau Fraîche', 'This fragrance combines citrus notes of lemon, bergamot, and rosewood with a hint of spice and musk, creating a bright and uplifting scent that\'s perfect for summer.', 'Citrus', 3895.00, 30, 100, 'Male', 'Versace Man Eau Fraîche.jpg', 'Available', '2024-05-05 00:52:49'),
(8, 'Allure Homme Sport', 'Allure Homme Sport features notes of mandarin orange, neroli, and cedar, creating a refreshing and dynamic fragrance that\'s perfect for the active man.', 'Citrus', 3850.00, 35, 100, 'Male', 'Allure Homme Sport.jpg', 'Available', '2024-05-05 00:55:37'),
(9, 'Guerlain Mon Guerlain', 'Mon Guerlain features a woody oriental base of sandalwood and vanilla alongside lavender and jasmine, creating a timeless and sophisticated fragrance that\'s both comforting and elegant.', 'Woody', 6672.00, 50, 100, 'Female', 'Guerlain Mon Guerlain.jpg', 'Available', '2024-05-05 00:58:40'),
(10, 'Hypnotic Poison', 'Hypnotic Poison is an oriental vanilla perfume that sets an enticing mood for an evening spent with your special someone. Launched in 1998, this mesmerizingly rich and dark fragrance was crafted by perfumer Annick Menardo. The top notes are a delectable blend of fuzzy and soft apricot, lush plum and tropical coconut.', 'Vanilla', 1367.00, 50, 100, 'Female', 'Hypnotic Poison.jpg', 'Available', '2024-05-05 01:01:27'),
(11, 'Dior Homme', ' Dior Homme features iris as a prominent note, giving it a powdery and floral quality. It\'s balanced with leather and vetiver, creating a refined and elegant scent that\'s perfect for the modern man.', 'Floral', 3550.00, 45, 100, 'Male', 'Dior Homme.jpg', 'Available', '2024-05-05 01:04:02'),
(12, 'Light Blue', 'Light Blue by Dolce & Gabbana features notes of Sicilian lemon, apple, and bamboo, combined with floral notes such as jasmine and white rose, creating a fresh and vibrant fragrance that\'s perfect for summer.', 'Floral', 2620.00, 30, 100, 'Female', 'light blue.jpg', 'Available', '2024-05-05 01:13:09'),
(13, 'Flowerbomb', 'Flowerbomb by Viktor&Rolf features a floral explosion of jasmine, rose, and orchid, combined with patchouli and vanilla, creating a sensual and addictive fragrance that\'s perfect for evenings and special occasions.', 'Floral', 3499.00, 50, 100, 'Female', 'Flowerbomb.jpg', 'Available', '2024-05-05 01:15:59'),
(14, 'Gucci Bloom', 'Gucci Bloom is a rich white floral fragrance that captures the spirit of contemporary, diverse, and authentic women. With notes of tuberose, jasmine, and Rangoon creeper, it\'s lush and evocative.', 'Floral', 6200.00, 30, 50, 'Female', 'Gucci Bloom - Gucci  100.jpg', 'Available', '2024-06-10 03:45:33'),
(15, 'Daisy', 'Daisy is a fresh and youthful fragrance with notes of violet, strawberry, and jasmine, reminiscent of a sunny day in a flower-filled meadow.', 'Floral', 4500.00, 30, 100, 'Female', 'Daisy - Marc Jacobs 100ML.jpg', 'Available', '2024-06-13 12:29:22'),
(16, 'Mon Paris', 'Mon Paris is a passionate and romantic fragrance with fruity and floral notes of strawberry, jasmine, and patchouli, capturing the essence of love and desire.', 'Floral', 5800.00, 20, 50, 'Female', 'Mon Paris - Yves Saint Laurent 50.jpg', 'Available', '2024-06-13 12:30:57'),
(17, 'L Eau d Issey', 'L Eau d Issey is a refreshing and aquatic fragrance with notes of lotus, cyclamen, and freesia, capturing the purity and serenity of water.', 'Fresh', 5000.00, 20, 125, 'Female', 'Issey Miyake L Eau d Issey Pour Homme.jpg', 'Available', '2024-06-13 12:38:07'),
(18, 'Bright Crystal', 'right Crystal is a sparkling and vibrant fragrance with notes of pomegranate, peony, and musk, embodying the allure of a fresh and luminous bouquet.', 'Fresh', 4900.00, 20, 50, 'Female', 'Versace Bright Crystal.jpg', 'Available', '2024-06-13 12:40:40'),
(19, 'Omnia Crystalline', 'Omnia Crystalline is a fresh and luminous fragrance with notes of bamboo, pear, and lotus blossom, evoking the purity and clarity of crystal-clear water.', 'Fresh', 4600.00, 20, 100, 'Female', 'Bvlgari Omnia Crystalline.jpg', 'Available', '2024-06-13 12:52:59'),
(20, 'Angel', 'Angel by Thierry Mugler is a captivating blend of sweet vanilla, patchouli, and fruity notes, offering a unique and addictive scent that\'s perfect for evening wear.', 'Vanilla', 8500.00, 20, 100, 'Female', 'Thierry Mugler Angel.jpg', 'Available', '2024-06-13 12:58:33'),
(21, 'Black Orchid', 'Black Orchid is a luxurious and mysterious fragrance with notes of black truffle, black orchid, and patchouli, exuding elegance and sensuality.', 'Vanilla', 12000.00, 20, 100, 'Female', 'Black Orchid - Tom Ford 100.jpg', 'Available', '2024-06-13 13:00:10'),
(22, 'Baccarat Rouge 540', 'Baccarat Rouge 540 is a sophisticated and intoxicating fragrance with notes of jasmine, saffron, and cedarwood, enveloping the wearer in a veil of warmth and sensuality.', 'Vanilla', 6500.00, 20, 100, 'Female', 'Maison Francis Kurkdjian Baccarat Rouge 540.jpg', 'Available', '2024-06-13 13:04:46'),
(23, 'J adore Injoy', 'J adore Injoy is a vibrant and refreshing fragrance with notes of citrus, sea salt, and floral accords, capturing the essence of a joyful summer day by the sea.', 'Citrus', 8300.00, 20, 100, 'Female', 'Dior J adore Injoy.jpg', 'Available', '2024-06-13 13:12:14'),
(24, 'Lime Basil & Mandarin', 'A zesty and invigorating scent featuring the freshness of lime, basil, and mandarin, perfect for those who love citrusy fragrances with a twist.', 'Citrus', 7500.00, 20, 100, 'Female', 'Jo Malone London Lime Basil & Mandarin.jpg', 'Available', '2024-06-13 13:17:16'),
(25, 'Blu Mediterraneo Fico di Amalfi', 'Fico di Amalfi is a vibrant citrus fragrance with notes of bergamot, lemon, and fig, capturing the essence of the picturesque Amalfi Coast in Italy.', 'Citrus', 9000.00, 20, 100, 'Female', 'Acqua di Parma Blu Mediterraneo - Fico di Amalfi.jpg', 'Available', '2024-06-13 13:23:10'),
(26, 'Eau des Merveilles', 'Eau des Merveilles is a magical and enchanting fragrance with notes of orange, ambergris, and patchouli, evoking the wonder and allure of a starry night sky.', 'Citrus', 8900.00, 20, 100, 'Female', 'Hermes Eau des Merveilles.jpg', 'Available', '2024-06-13 13:26:58'),
(27, 'Orange Sanguine', 'Orange Sanguine is a refreshing and energizing fragrance with notes of blood orange, bitter orange, and jasmine, reminiscent of a sunny day in a citrus grove.', 'Citrus', 8000.00, 20, 100, 'Female', 'Atelier Cologne Orange Sanguine.jpg', 'Available', '2024-06-13 13:28:51'),
(28, 'Chloe Nomade', 'Nomade is a modern and elegant fragrance with notes of oakmoss, mirabelle plum, and freesia, capturing the spirit of adventurous and free-spirited women.', 'Woody', 7650.00, 20, 100, 'Female', 'Chloe Nomade.jpg', 'Available', '2024-06-13 13:34:14'),
(29, 'Acqua di Giò Profumo', 'Acqua di Giò Profumo is a sophisticated and intense fragrance with notes of sea, incense, and patchouli, evoking the allure of the Mediterranean sea at night.', 'Woody', 8560.00, 20, 100, 'Male', 'Giorgio Armani Acqua di Giò Profumo.jpg', 'Available', '2024-06-13 13:36:53'),
(30, 'Santal Blush', 'Tom Ford Santal Blush is an enchanting and exotic fragrance that blends creamy sandalwood with a symphony of spices and florals. It evokes the mystique of the Orient with hints of cinnamon, fenugreek, and ylang-ylang, offering a warm, sensual aroma that lingers beautifully on the skin.', 'Woody', 7900.00, 20, 50, 'Female', 'Tom Ford Santal Blush.png', 'Available', '2024-06-13 13:43:27'),
(31, 'Libre Intense', 'Yves Saint Laurent Libre Intense is a bold and seductive fragrance featuring a blend of lavender essence from France, Moroccan orange blossom, and a woody base of cedarwood. This intense version of the classic Libre is richer and more powerful, exuding confidence and elegance.', 'Woody', 8990.00, 20, 100, 'Female', 'Yves Saint Laurent Libre Intense.jpg', 'Available', '2024-06-13 13:45:39'),
(32, 'Guilty Absolute', 'Gucci Guilty Absolute is an intriguing and distinctive scent with a blend of leather, patchouli, and goldenwood. This fragrance offers an earthy, sensual, and warm experience, making it perfect for those who seek a bold and unique signature scent.', 'Woody', 8000.00, 20, 100, 'Female', 'Gucci Guilty Absolute.jpg', 'Available', '2024-06-13 13:47:16'),
(33, 'Wood Sage & Sea Salt', 'Jo Malone Wood Sage & Sea Salt is a refreshing and earthy fragrance that captures the essence of the coast. With notes of ambrette seeds, sea salt, and sage, it creates a woody yet fresh scent that is perfect for everyday wear, evoking a sense of freedom and nature.', 'Woody', 7000.00, 20, 100, 'Female', 'Jo Malone London Wood Sage & Sea Salt.jpg', 'Available', '2024-06-13 13:48:41'),
(34, 'Tom Ford Oud Wood', 'Tom Ford Oud Wood is an opulent and exotic fragrance featuring the rare and precious oud wood. Blended with sandalwood, rosewood, and spices, it creates a rich, complex, and captivating scent that exudes luxury and sophistication.', 'Woody', 9000.00, 20, 50, 'Male', 'Tom Ford Oud Wood.jpg', 'Available', '2024-06-13 13:50:41'),
(35, 'Jean Paul Gaultier Le Male', 'Jean Paul Gaultier Le Male is an iconic fragrance known for its distinctive floral heart. The scent features a blend of lavender, orange blossom, and cinnamon, creating a sweet and spicy aroma. The base of vanilla, tonka bean, and sandalwood adds a creamy and comforting finish.', 'Floral', 5000.00, 20, 75, 'Male', 'Jean Paul Gaultier Le Male.jpg', 'Available', '2024-06-13 14:00:27'),
(36, 'Versace Eros Flame', 'Versace Eros Flame is a passionate and fiery fragrance with floral undertones. The scent opens with a burst of citrus and black pepper, followed by a heart of geranium and rose. The base of cedarwood, tonka bean, and vanilla creates a warm and sensual finish, perfect for evening wear.', 'Floral', 5300.00, 20, 100, 'Male', 'Versace Eros Flame.jpg', 'Available', '2024-06-13 14:02:23'),
(37, 'Jo Malone London Orange Blossom', 'Jo Malone London Orange Blossom is a vibrant and uplifting fragrance that features the pure scent of orange blossom. This floral fragrance is enhanced with clementine flower, water lily, and orris, creating a fresh and radiant scent that is perfect for spring and summer.', 'Floral', 7000.00, 20, 100, 'Male', 'Jo Malone London Orange Blossom - Jo Malone 100.jpg', 'Available', '2024-06-13 14:03:51'),
(38, 'Amouage Reflection Man', 'Amouage Reflection Man is a sophisticated and elegant fragrance that blends floral and woody notes. The scent opens with fresh rosemary and red pepper berries, leading to a heart of jasmine, neroli, and orris. The base of sandalwood, cedar, and patchouli adds a warm and comforting finish.', 'Floral', 10000.00, 20, 100, 'Male', 'Amouage Reflection Man.jpg', 'Available', '2024-06-13 14:05:30'),
(39, 'Dior Sauvage Eau de Toilette', 'Dior Sauvage Eau de Toilette is a fresh and powerful fragrance inspired by wide-open spaces. It features top notes of Calabrian bergamot and pepper, followed by a heart of Sichuan pepper and lavender. The base of ambroxan and cedar adds a woody, masculine finish to this invigorating scent.', 'Fresh', 7600.00, 20, 100, 'Male', 'Dior Sauvage.jpg', 'Available', '2024-06-13 14:09:40'),
(40, 'CK One', 'Calvin Klein CK One is a fresh and clean unisex fragrance that combines citrus and green notes. It features top notes of lemon, bergamot, and pineapple, followed by a heart of jasmine, rose, and nutmeg. The base of musk and amber adds a subtle warmth to this refreshing scent.', 'Fresh', 3500.00, 20, 100, 'Male', 'Calvin Klein CK One.jpg', 'Available', '2024-06-13 14:10:38'),
(41, 'Burberry Touch for Men', 'Burberry Touch for Men is a fresh and aromatic fragrance with a modern appeal. It features top notes of artemisia, violet leaves, and mandarin, followed by a heart of nutmeg, white pepper, and cedar. The base of tonka bean, vetiver, and musk adds a warm and sensual finish.', 'Fresh', 4200.00, 20, 100, 'Male', 'Burberry Touch for Men.jpg', 'Available', '2024-06-13 14:11:50'),
(42, 'Tom Ford Neroli Portofino', 'Tom Ford Neroli Portofino is a fresh and vibrant fragrance inspired by the Italian Riviera. It features top notes of bergamot, lemon, and mandarin, followed by a heart of neroli and orange blossom. The base of amber and angelica adds a warm, luxurious touch to this bright scent.', 'Fresh', 7500.00, 20, 50, 'Male', 'Neroli Portofino - Tom Ford 50.jpg', 'Available', '2024-06-13 14:13:07'),
(43, 'Azzaro Chrome', 'Azzaro Chrome is a fresh and invigorating fragrance that combines citrus and aquatic notes. It features top notes of lemon, bergamot, and rosemary, followed by a heart of neroli and jasmine. The base of musk, oakmoss, and cedar adds a warm, masculine finish to this refreshing scent.', 'Fresh', 4200.00, 20, 100, 'Male', 'Azzaro Chrome.jpg', 'Available', '2024-06-13 14:14:33'),
(44, 'Valentino Uomo', 'Valentino Uomo is a refined and elegant fragrance that blends fresh and warm notes. It features top notes of bergamot and myrtle, followed by a heart of roasted coffee and gianduja cream. The base of cedar and vanilla creates a rich and luxurious finish, making it suitable for both daytime and evening wear.\r\n', 'Vanilla', 7200.00, 20, 100, 'Male', 'Valentino Uomo.jpg', 'Available', '2024-06-13 14:17:04'),
(45, 'Maison Margiela Replica By the Fireplace', 'Maison Margiela Replica By the Fireplace is a warm and cozy fragrance that evokes the feeling of sitting by a fire on a cold winter\'s night. It features top notes of pink pepper and orange blossom, followed by a heart of chestnut and guaiac wood. The base of vanilla, Peru balsam, and cashmeran creates a comforting and indulgent finish.', 'Vanilla', 8500.00, 20, 100, 'Male', 'Maison Margiela Replica By the Fireplace.jpg', 'Available', '2024-06-13 14:18:46'),
(46, 'Montblanc Legend Night', 'Montblanc Legend Night is a sophisticated and elegant fragrance that features a blend of woody and sweet notes. It opens with top notes of bergamot and clary sage, followed by a heart of cedarwood and violet. The base of black vanilla, patchouli, and musk adds a warm and sensual finish.', 'Vanilla', 5000.00, 20, 100, 'Male', 'Montblanc Legend Night.jpg', 'Available', '2024-06-13 14:20:21'),
(47, 'Hugo Boss The Scent Private Accord', 'Hugo Boss The Scent Private Accord is a luxurious and seductive fragrance that combines fresh and warm notes. It opens with top notes of ginger and bergamot, followed by a heart of mocha and maninka fruit. The base of cocoa and vanilla creates a rich and indulgent finish.', 'Vanilla', 6000.00, 20, 100, 'Male', 'Hugo Boss The Scent Private Accord.jpg', 'Available', '2024-06-13 14:21:57'),
(48, 'Dunhill Icon Absolute', 'Dunhill Icon Absolute is a sophisticated and elegant fragrance that features a blend of spicy and woody notes. It opens with top notes of bergamot and black pepper, followed by a heart of saffron and black rose. The base of oud, tobacco leaf, and vanilla creates a rich and luxurious finish.', 'Vanilla', 7000.00, 20, 100, 'Male', 'Dunhill Icon Absolute.jpg', 'Available', '2024-06-13 14:23:24'),
(49, 'Clinique Happy For Men', 'Clinique Happy For Men is a fresh and uplifting fragrance that combines citrus and green notes. It features top notes of lime, lemon, and mandarin, followed by a heart of freesia, jasmine, and lily of the valley. The base of cypress, musk, and cedar adds depth and longevity to this bright scent.', 'Citrus', 4000.00, 20, 100, 'Male', 'Clinique Happy For Men.jpg', 'Available', '2024-06-13 14:26:31'),
(50, 'Paco Rabanne Invictus', 'Paco Rabanne Invictus is a fresh and powerful fragrance that combines citrus and woody notes. It features top notes of grapefruit and sea notes, followed by a heart of bay leaf and jasmine. The base of guaiac wood, oakmoss, and ambergris adds depth and sophistication.', 'Citrus', 5500.00, 20, 100, 'Male', 'Paco Rabanne Invictus.jpg', 'Available', '2024-06-13 14:28:41'),
(51, 'Acqua di Parma Colonia Oud', 'Acqua di Parma Colonia Oud is a luxurious and sophisticated fragrance that blends citrus and woody notes. It opens with fresh notes of bergamot and orange, followed by a heart of oud wood and coriander. The base of sandalwood, patchouli, and leather adds depth and richness to this elegant scent.', 'Woody', 7800.00, 20, 50, 'Male', 'Acqua di Parma Colonia Oud.jpg', 'Available', '2024-06-13 14:31:16'),
(52, 'Dolce & Gabbana The One for Men', 'Dolce & Gabbana The One for Men is a sophisticated and elegant fragrance that features a woody base. It opens with notes of grapefruit, coriander, and basil, followed by a heart of cardamom, ginger, and orange blossom. The base of cedarwood, amber, and tobacco adds warmth and depth to this refined scent.', 'Woody', 5800.00, 20, 100, 'Male', 'Dolce & Gabbana The One for Men.jpg', 'Available', '2024-06-13 14:32:36'),
(53, 'Versace Pour Homme Oud Noir', 'Versace Pour Homme Oud Noir is a luxurious and exotic fragrance that features a blend of woody and spicy notes. It opens with top notes of bitter orange, neroli, and black pepper, followed by a heart of cardamom, saffron, and oud wood. The base of patchouli, leatherwood, and sandalwood adds depth and warmth to this opulent scent.', 'Woody', 7000.00, 20, 100, 'Male', 'Versace Pour Homme Oud Noir.jpg', 'Available', '2024-06-13 14:34:13');

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

DROP TABLE IF EXISTS `userinfo`;
CREATE TABLE `userinfo` (
  `userid` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `midname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `usertype` varchar(50) NOT NULL DEFAULT 'Client',
  `image` varchar(100) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billing_details`
--
ALTER TABLE `billing_details`
  ADD PRIMARY KEY (`billing_id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `pay_id` (`pay_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `UQ_cart_id` (`cart_id`),
  ADD KEY `FK_items_TO_cartlist` (`prod_no`),
  ADD KEY `FK_customerprofile_TO_cartlist` (`userid`);

--
-- Indexes for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD PRIMARY KEY (`order_id`,`prod_no`),
  ADD KEY `FK_items_TO_orderdetail` (`prod_no`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `FK_customerprofile_TO_orders` (`userid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`prod_no`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `prod_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billing_details`
--
ALTER TABLE `billing_details`
  ADD CONSTRAINT `billing_details_ibfk_1` FOREIGN KEY (`pay_id`) REFERENCES `payment` (`pay_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `billing_details_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `userinfo` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`prod_no`) REFERENCES `products` (`prod_no`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `userinfo` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `orderdetail_ibfk_1` FOREIGN KEY (`prod_no`) REFERENCES `products` (`prod_no`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderdetail_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `userinfo` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `userinfo` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
