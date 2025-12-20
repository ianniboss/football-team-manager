-- --------------------------------------------------------
-- Football Team Manager - Add Image Columns
-- Run this script to add image storage capabilities
-- --------------------------------------------------------

-- Add image column to joueur table (stores filename like 'kylian_mbappe_1.jpg')
ALTER TABLE joueur ADD COLUMN image VARCHAR(255) DEFAULT NULL;

-- Add stadium image column to rencontre table (stores filename like 'stade_velodrome.jpg')
ALTER TABLE rencontre ADD COLUMN image_stade VARCHAR(255) DEFAULT NULL;
