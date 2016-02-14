import os
import sys

file = open('high_school_list_raw.txt', 'r')
lines = file.readlines()

postcode2tel = {
	'310':'0571',
	'311':'0571',
	'312':'0575',
	'313':'0572',
	'314':'0573',
	'315':'0574',
	'316':'0580',
	'317':'0576',
	'318':'0576',
	'325':'0577',
	'323':'0578',
	'324':'0570',
	'322':'0579',
	'321':'0579',
	'202':'0580',
	'306':'0580',
}


for line in lines:
	attr = line.split(' ')
	postcode_area = attr[3][0:3]
	print("INSERT INTO `school_list`(`id`, `school`, `addr`, `postcode`, `telcode`) VALUES ('{}','{}','{}','{}','{}');".format(attr[0], attr[1], attr[2], attr[3], postcode2tel[postcode_area]))