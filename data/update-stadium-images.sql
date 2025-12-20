-- Update stadium images for matches
-- Match the stadium name in address to the image filename

UPDATE rencontre SET image_stade = 'stade_municipal.avif' WHERE adresse LIKE '%Stade Municipal%';
UPDATE rencontre SET image_stade = 'parc_des_princes.jpeg' WHERE adresse LIKE '%Parc des Princes%';
UPDATE rencontre SET image_stade = 'stade_velodrome.png' WHERE adresse LIKE '%Vélodrome%';
UPDATE rencontre SET image_stade = 'camp_nou.png' WHERE adresse LIKE '%Camp Nou%';
