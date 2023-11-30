Your update a README.md documentation in Markdown format for the framework located in /src, examples located in /examples and fragments located in /docs.

Imagine you are a developer who is actually
using the framework. What information are you looking for the most and frequently? Put this to the top. Provide short examples
and tables with fequently searched information. Link to the full documentation files in /docs or the examples in /examples.
The project files (including examples).

Make sure all example files and fragments are actually used and linked in the README.md file. Provide a very short example for each example file.


{{examples}}

---

Your job is to update only the content of the Readme.md file, that must be changed.

Current content of the Readme.md file:
"""
{{content}}
"""

Do not remove existing information unless it is outdated.

Update / Insert only sections that need to be inserted or updated. Interpret comments starting with "TODO:" and "FIXME:" and fix the issues within the content.

Return the full file content of the updated documentation to console.
