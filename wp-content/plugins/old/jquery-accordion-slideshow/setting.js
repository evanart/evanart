/**
 *     Jquery accordion slideshow
 *     Copyright (C) 2011 - 2013 www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */	

function JaS_submit()
{
	if(document.JaS_form.JaS_Gallery.value=="")
	{
		alert("Please select the gallery name.")
		document.JaS_form.JaS_Gallery.focus();
		return false;
	}
	else if(document.JaS_form.JaS_Location.value=="")
	{
		alert("Please enter the image folder(Where you have your images).")
		document.JaS_form.JaS_Location.focus();
		return false;
	}
	else if(document.JaS_form.JaS_timeout.value=="" || isNaN(document.JaS_form.JaS_timeout.value))
	{
		alert("Please enter Time between each slide (in ms), only number.")
		document.JaS_form.JaS_timeout.focus();
		document.JaS_form.JaS_timeout.select();
		return false;
	}
	else if(document.JaS_form.JaS_width.value=="" || isNaN(document.JaS_form.JaS_width.value))
	{
		alert("Please enter Width of the container (in px), only number.")
		document.JaS_form.JaS_width.focus();
		document.JaS_form.JaS_width.select();
		return false;
	}
	else if(document.JaS_form.JaS_height.value=="" || isNaN(document.JaS_form.JaS_height.value))
	{
		alert("Please enter Height of the container (in px), only number.")
		document.JaS_form.JaS_height.focus();
		document.JaS_form.JaS_height.select();
		return false;
	}
	else if(document.JaS_form.JaS_slideWidth.value=="" || isNaN(document.JaS_form.JaS_slideWidth.value))
	{
		alert("Please enter Width of each slide (in px), only number.")
		document.JaS_form.JaS_slideWidth.focus();
		document.JaS_form.JaS_slideWidth.select();
		return false;
	}
	else if(document.JaS_form.JaS_slideHeight.value=="" || isNaN(document.JaS_form.JaS_slideHeight.value))
	{
		alert("Please enter Height of each slide (in px), only number.")
		document.JaS_form.JaS_slideHeight.focus();
		document.JaS_form.JaS_slideHeight.select();
		return false;
	}
	else if(document.JaS_form.JaS_tabWidth.value=="" || isNaN(document.JaS_form.JaS_tabWidth.value))
	{
		alert("Width of each slide's tab (when clicked it opens the slide), only number.")
		document.JaS_form.JaS_tabWidth.focus();
		document.JaS_form.JaS_tabWidth.select();
		return false;
	}
	else if(document.JaS_form.JaS_speed.value=="" || isNaN(document.JaS_form.JaS_speed.value))
	{
		alert("Please enter Speed of the slide transition (in ms), only number.")
		document.JaS_form.JaS_speed.focus();
		document.JaS_form.JaS_speed.select();
		return false;
	}
}

function JaS_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_JaS_display.action="options-general.php?page=jquery-accordion-slideshow/jquery-accordion-slideshow.php&AC=DEL&DID="+id;
		document.frm_JaS_display.submit();
	}
}	

function JaS_redirect()
{
	window.location = "options-general.php?page=jquery-accordion-slideshow/jquery-accordion-slideshow.php";
}

function JaS_help()
{
	window.open("http://www.gopiplus.com/work/2011/12/21/jquery-accordion-slideshow-wordpress-plugin/");
}