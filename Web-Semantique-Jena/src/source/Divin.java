package source;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.InputStream;

import org.apache.jena.query.Query;
import org.apache.jena.query.QueryExecution;
import org.apache.jena.query.QueryExecutionFactory;
import org.apache.jena.query.QueryFactory;
import org.apache.jena.query.QuerySolution;
import org.apache.jena.query.ResultSet;
import org.apache.jena.rdf.model.Literal;
import org.apache.jena.rdf.model.Model;
import org.apache.jena.rdf.model.ModelFactory;
import org.apache.jena.rdf.model.Property;
import org.apache.jena.rdf.model.RDFNode;
import org.apache.jena.rdf.model.Resource;
import org.apache.jena.util.FileManager;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.ss.usermodel.Sheet;
import org.apache.poi.ss.usermodel.Workbook;
import org.apache.poi.ss.usermodel.WorkbookFactory;


public class Divin {
	
	public static void findExcelData() {

		String fileName = "../wine2.xlsx";
		System.out.println("OKKKKK");
	    final File file = new File(fileName);

			try {
		        final Workbook workbook = WorkbookFactory.create(file);
		        final Sheet sheet = workbook.getSheet("wswine2");

		        int index = 1;
		        Model model = ModelFactory.createDefaultModel();
		        Row row = sheet.getRow(index++);
		        while (row != null) {
		        	doRow(model,row);
		            row = sheet.getRow(index++);
		        }
		        try {
					FileOutputStream fileToWrite = new FileOutputStream("test2.rdf",true);
					model.write(fileToWrite, "RDF/XML-ABBREV");
				} catch (FileNotFoundException e) {
					e.printStackTrace();
				}


		    } catch (Exception e) {
		        e.printStackTrace();
		    }
	    
	}
	
	private static void doRow(Model model, Row row) {
		
		/** remplissage des lignes **/
		String level1	 	= row.getCell(0)==null ? "" : row.getCell(0).getStringCellValue();
		String level1Href   = row.getCell(1)==null ? "" : row.getCell(1).getStringCellValue();
		String wineLink     = row.getCell(2)==null ? "" : row.getCell(2).getStringCellValue();
		String wineURI 		= row.getCell(3)==null ? "" : row.getCell(3).getStringCellValue();
		String winelabel    = row.getCell(4)==null ? "" : row.getCell(4).getStringCellValue();
		String keyWords	   	= row.getCell(5)==null ? "" : row.getCell(5).getStringCellValue();
		String domain	   	= row.getCell(6)==null ? "" : row.getCell(6).getStringCellValue();
		String description	= row.getCell(7)==null ? "" : row.getCell(7).getStringCellValue();
		String infodomain	= row.getCell(8)==null ? "" : row.getCell(8).getStringCellValue();
		String pictureSrc  	= row.getCell(9)==null ? "" : row.getCell(9).getStringCellValue();
		String domainPicture= row.getCell(10)==null ? "" : row.getCell(10).getStringCellValue();
		String temp  		= row.getCell(11)==null ? "" : row.getCell(11).getStringCellValue();
	
		if(!temp.contains("°"))
			temp = "";
		String nsWine = "http://divin.com/wine#";
		
		/** Propriétés rdf **/
		Property level1Prop		   = model.createProperty(nsWine + "Level1");
		Property level1HrefProp    = model.createProperty(nsWine + "Level1-Href");
		Property wineLinkProp	   = model.createProperty(nsWine + "wineLink");
		Property winelabelProp 	   = model.createProperty(nsWine + "Label");
		Property keyWordsProp 	   = model.createProperty(nsWine + "KeyWords");
		Property domainProp 	   = model.createProperty(nsWine + "Domain");
		Property descriptionProp   = model.createProperty(nsWine + "Description");
		Property infodomainProp    = model.createProperty(nsWine + "InfoDomain");
		Property pictureSrcProp    = model.createProperty(nsWine + "PictureSrc");
		Property domainPictureProp = model.createProperty(nsWine + "DomainPicture");
		Property tempProp          = model.createProperty(nsWine + "Temperature");

		model.setNsPrefix("nsWine", nsWine);
		
		Resource wine
		  = model.createResource(wineURI)
				 .addProperty(level1Prop, level1)	
		         .addProperty(level1HrefProp, level1Href)
		         .addProperty(wineLinkProp, wineLink)
		         .addProperty(winelabelProp, winelabel)
		         .addProperty(keyWordsProp, keyWords)
		         .addProperty(domainProp, domain)
		         .addProperty(descriptionProp, description)
		         .addProperty(infodomainProp, infodomain)
		         .addProperty(pictureSrcProp, pictureSrc)
		         .addProperty(domainPictureProp, domainPicture)
		         .addProperty(tempProp,temp);
	}
	
	private static void readFile(String inputFileName) {
		Model model = ModelFactory.createDefaultModel();
		InputStream in = FileManager.get().open(inputFileName);
		if (in == null) {
		    throw new IllegalArgumentException(
		                                 "File: " + inputFileName + " not found");
		}
		model.read(in, null, "RDF/XML");
		query(model);
	}

	public static void query(Model model) {
		
		  String queryString = "prefix nsWine: <http://divin.com/wine#> select * where {"
								 + "?uri nsWine:Label ?label."
								 + "?uri nsWine:KeyWords ?k_w."
								 + "?uri nsWine:PictureSrc ?picture."
								 + "?uri nsWine:Description ?desc."
								 + "FILTER (( regex(?label, '.*Château.*'))) }"; 
		  Query query = QueryFactory.create(queryString) ;
		  try (QueryExecution qexec = QueryExecutionFactory.create(query, model)) {
		    ResultSet results = qexec.execSelect() ;
		    for ( ; results.hasNext() ; )
		    {
		      QuerySolution soln = results.nextSolution() ;
		      RDFNode x = soln.get("uri") ;       // Get a result variable by name.
		      Resource r = soln.getResource("fff") ; // Get a result variable - must be a resource
		      Literal l = soln.getLiteral("desc") ;   // Get a result variable - must be a literal
		      System.out.println("X : " + x + " & R : " + r + " & L : " + l);
		    }
		  }
	}
	
	public static void main(String[] args) {
//		findExcelData();
		readFile("test2.rdf");

	}

}
