#!/usr/local/bin/python 

import os
#from transliterate import slugify
from slugify import slugify
import datetime, xlrd
import unicodecsv
import sys

def xls2csv (fname, csv_filename):
    # Converts an Excel file to a CSV file.
    # If the excel file has multiple worksheets, only the first worksheet is converted.
    # Uses unicodecsv, so it will handle Unicode characters.
    # Uses a recent version of xlrd, so it should handle old .xls and new .xlsx equally well.

    fh = open('/usr/local/www/yii2-consumption/runtime/upload/month/' +csv_filename,"wb")
    csv_out = unicodecsv.writer(fh, encoding='utf-8',delimiter='\t')
    book = xlrd.open_workbook(fname)
    print("The number of worksheets is {0}".format(book.nsheets))
    print("Worksheet name(s): {0}".format(book.sheet_names()))
    
    sheet = book.sheet_by_index(0)
    for rowx in range(sheet.nrows):
        cell_end = sheet.cell(rowx, 0)
        if cell_end.value == '':
            continue
        for colx in range(sheet.ncols):
            cell = sheet.cell(rowx, colx)
            if sheet.cell_type(rowx, colx) == xlrd.XL_CELL_EMPTY:
                sheet._cell_values[rowx][colx] = 'NULL'
            if (cell.ctype == xlrd.XL_CELL_NUMBER and (colx == 1 or colx == 4 or colx == 8)):
#                print ('looks like %0*d', int(cell.value))
                sheet._cell_values[rowx][colx] = int(cell.value)
            if (cell.ctype == xlrd.XL_CELL_DATE ):
 #               print ('looks like %s', str(cell.value))
                a1 = cell.value
                a1_as_datetime = datetime.datetime(*xlrd.xldate_as_tuple(a1, book.datemode))
                #print (a1_as_datetime.date())
  #       #       print ('datetime: %s' % a1_as_datetime)
                sheet._cell_values[rowx][colx] = a1_as_datetime.date()
        csv_out.writerow(sheet.row_values(rowx))
    fh.close()

excelfiles = [x for x in os.listdir('/usr/local/www/yii2-consumption/runtime/upload/month/') if x.endswith(".xls")]

#print(arr_txt)
# for i in excelfiles:
    # print (i)


#print(sys.argv);
#filename = sys.argv[1] + '.xls';
#print(filename);
for i, filename in enumerate(excelfiles):
    base, ext = os.path.splitext(filename)
    print ('base - ',base)
    print (ext)
#"The sum of 1 + 2 is {0}".format(1+2)    
    newname = slugify(base) + '.csv'
#    newname =  '/consumption.csv'
    print(filename)
    print (newname)
    xls2csv('/usr/local/www/yii2-consumption/runtime/upload/month/' + filename,newname)
    uid = 80
    gid = 80
    os.chown('/usr/local/www/yii2-consumption/runtime/upload/month/' +newname, gid,uid)



 
