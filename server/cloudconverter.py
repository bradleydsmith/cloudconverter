#!/usr/bin/python

import MySQLdb
from env import *
import time
import os
import requests

while True:
	db = MySQLdb.connect(host=MYSQL_SERVER, user=MYSQL_USER, passwd=MYSQL_PASS, db=MYSQL_DB)
	cursor = db.cursor()
	resultCount = cursor.execute("SELECT id,genid,upfilename,fileconvert from uploads WHERE status = 0 ORDER BY id ASC LIMIT 1")
	if (resultCount == 1):
		result = cursor.fetchone()
		print result
		idStr = str(result[0])
		genid = result[1]
		downURL = result[2]
		convertFormat = result[3]
		db.close();
		db = MySQLdb.connect(host=MYSQL_SERVER, user=MYSQL_USER, passwd=MYSQL_PASS, db=MYSQL_DB)
		cursor = db.cursor()
		print "UPDATE uploads SET status = 1 WHERE id = " + idStr
		cursor.execute("UPDATE uploads SET status = 1 WHERE id = " + idStr)
		db.commit();
		db.close();
		outputFileName = genid + "_conv." + convertFormat
		os.system("wget " + downURL);
		os.system("ffmpeg -i " + genid + " " + outputFileName)
		r = requests.get(UPLOAD_REQUEST_URL);
		uploadURL = r.text
		print uploadURL
		with open(outputFileName, 'rb') as f:
			r = requests.post(uploadURL, files={'convertFile': f})
			publicURL = r.text
			print publicURL
		db = MySQLdb.connect(host=MYSQL_SERVER, user=MYSQL_USER, passwd=MYSQL_PASS, db=MYSQL_DB)
		cursor = db.cursor()
		cursor.execute("""UPDATE uploads SET downfilename = %s WHERE id = %s""", (publicURL, idStr))
		cursor.execute("UPDATE uploads SET status = 2 WHERE id = " + idStr)
		db.commit()
		db.close()
		print "Deleting"
		os.system("rm " + genid)
		os.system("rm " + outputFileName)
		print "Finished"
	else:
		print "Sleeping..."
		time.sleep(5)
