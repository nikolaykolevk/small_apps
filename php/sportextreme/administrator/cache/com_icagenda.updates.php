<?php
class LiveUpdateCom_icagendaCache {
	public $update = array("stuck" => "0", "lastcheck" => "1578855654", "updatedata" => "Tzo4OiJzdGRDbGFzcyI6ODp7czo5OiJzdXBwb3J0ZWQiO2I6MTtzOjU6InN0dWNrIjtiOjA7czo3OiJ2ZXJzaW9uIjtzOjY6IjMuNy4xMSI7czo0OiJkYXRlIjtzOjEwOiIyMDE5LTEyLTE5IjtzOjk6InN0YWJpbGl0eSI7czo2OiJzdGFibGUiO3M6MTE6ImRvd25sb2FkVVJMIjtzOjY0OiJodHRwczovL3d3dy5qb29tbGljLmNvbS9pY3JzL2ljYWdlbmRhLTMtNy0xMS9pY2FnZW5kYV8zLTctMTEtemlwIjtzOjc6ImluZm9VUkwiO3M6NDQ6Imh0dHBzOi8vd3d3Lmpvb21saWMuY29tL2ljcnMvaWNhZ2VuZGEtMy03LTExIjtzOjEyOiJyZWxlYXNlbm90ZXMiO3M6MTYxMDoiPGgyPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImNvbG9yOiAjOTkzMzAwOyI+aUM8L3NwYW4+PHNwYW4gc3R5bGU9ImNvbG9yOiAjODA4MDgwOyI+YWdlbmRhPHNwYW4gc3R5bGU9ImNvbG9yOiAjNjY2NjY2OyI+4oSiPC9zcGFuPjwvc3Bhbj4gMy43LjExPGJyIC8+PC9zdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogOHB0OyBjb2xvcjogIzMzMzMzMzsiPjIwMTkuMTIuMTk8L3NwYW4+PC9oMj48aHIgLz48cD48c3Ryb25nPjxzdHJvbmc+PHN0cm9uZz48YmlnPldlbGNvbWUgdG8gaUNhZ2VuZGEgMy43LjExIHJlbGVhc2UhPC9iaWc+PC9zdHJvbmc+PC9zdHJvbmc+PC9zdHJvbmc+PC9wPjxwPjxzdHJvbmc+PHN0cm9uZz5pQ2FnZW5kYSAzLjcgaW50cm9kdWNlcyBwcml2YWN5IHRvb2xzIGFuZCBpbXByb3ZlbWVudHMgdG8gaGVscCB5b3UgY29tcGx5IHdpdGggR0RQUiBFVS1yZWd1bGF0aW9uLjwvc3Ryb25nPjwvc3Ryb25nPjwvcD48cD48ZW0+PHNwYW4gc3R5bGU9ImNvbG9yOiAjZmYwMDAwOyI+PHNwYW4gc3R5bGU9ImNvbG9yOiAjMDAwMDAwOyI+QmFja3VwIGJlZm9yZSB1cGdyYWRlLCBhbmQgbWluaW11bSBwaHAgdmVyc2lvbiA1LjMuMTAuPC9zcGFuPiA8c3Ryb25nPjxiciAvPjwvc3Ryb25nPjwvc3Bhbj5Zb3UgY2FuIHVzZSB0aGlzIHZlcnNpb24gb24gam9vbWxhIDMgKG1pbmltdW0gPC9lbT48ZW0+PGVtPjMuMi40KTwvZW0+LjwvZW0+PC9wPjxwPldlIHJlY29tbWVuZCBldmVyeSB1c2VyIHRvIGtlZXAgaUNhZ2VuZGEgdXAgdG8gZGF0ZS48c3Ryb25nPjxiciAvPjwvc3Ryb25nPjwvcD48cD7CoDwvcD48aDM+PHN0cm9uZz48c3Ryb25nPjxzdHJvbmc+PHN0cm9uZz48c3Ryb25nPjxzdHJvbmc+UmVsZWFzZSBOb3RlczxiciAvPjwvc3Ryb25nPjwvc3Ryb25nPjwvc3Ryb25nPjwvc3Ryb25nPjwvc3Ryb25nPjwvc3Ryb25nPjwvaDM+PHA+fiBbVEhFTUVdIENoYW5nZWQgOiBSZW1vdmUgY3NzIG1heC1oZWlnaHQgZm9yIGltYWdlIGluIGV2ZW50IGRldGFpbHMgdmlldzxiciAvPiMgW0xPV10gRml4ZWQgOiBtaXNzaW5nIGFsdCB0ZXh0IGZvciBpbWFnZXMuPC9wPjxoMz7CoDwvaDM+PGgzPjxzdHJvbmc+PHN0cm9uZz48c3Ryb25nPjxzdHJvbmc+PHN0cm9uZz48c3Ryb25nPkNoYW5nZWQgRmlsZXM8L3N0cm9uZz48L3N0cm9uZz48L3N0cm9uZz48L3N0cm9uZz48L3N0cm9uZz48L3N0cm9uZz48L2gzPjxwPn4gW0xJQlJBUlldIGxpYnJhcmllcy9pY19saWJyYXJ5L3RodW1iL2dldC5waHA8YnIgLz5+IFtUSEVNRV0gc2l0ZS90aGVtZXMvcGFja3MvZGVmYXVsdC9jc3MvZGVmYXVsdF9jb21wb25lbnQuY3NzPGJyIC8+fiBbVEhFTUVdIHNpdGUvdGhlbWVzL3BhY2tzL2ljX3JvdW5kZWQvY3NzL2ljX3JvdW5kZWRfY29tcG9uZW50LmNzczwvcD48aHIgLz48cD48c3BhbiBzdHlsZT0iY29sb3I6ICM4MDgwODA7Ij48ZW0+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogOHB0OyI+SWYgeW91IGVuY291bnRlciBhIGJ1ZywgdGhhbmtzIHRvIHJlcG9ydCBpdCBvbiB0aGUgSm9vbWxpQyBmb3J1bSwgc28gdGhhdCBpIGNhbiBwcm92aWRlIGEgZml4IGFzIGZhc3QgYXMgcG9zc2libGUuPC9zcGFuPjwvZW0+PC9zcGFuPjwvcD48cD7CoDwvcD4iO30=");
}
?>