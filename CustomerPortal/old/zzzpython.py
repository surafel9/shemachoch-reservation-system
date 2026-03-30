import os

# Use '.' for current directory
folder_path = '.'  # Current directory
output_file = 'combined_output.txt'  # Output file

# Print the current working directory to confirm
print(f"Current directory: {os.getcwd()}")

# Check if the folder path exists
if not os.path.exists(folder_path):
    print(f"Error: The folder path {folder_path} does not exist.")
else:
    print(f"Reading files from {folder_path}...")

# Open the output file in write mode
with open(output_file, 'w', encoding='utf-8') as output:
    # Flag to track if any .java files are found
    found_java_files = False
    
    # Loop through all files in the folder
    for filename in os.listdir(folder_path):
        # Check if it's a Java file (case-insensitive check)
        if filename.lower().endswith('.php'):
            found_java_files = True  # Found at least one Java file
            file_path = os.path.join(folder_path, filename)
            
            # Write the file name and markdown separator
            output.write("=" * 40 + "\n")
            output.write(f"File: {filename}\n")
            output.write("=" * 40 + "\n")
            
            # Write the Java code block markdown syntax
            output.write("```php\n")
            
            # Open the Java file and read its content
            try:
                with open(file_path, 'r', encoding='utf-8') as file:
                    content = file.read()
                    output.write(content)  # Write the content inside the Java block
            except Exception as e:
                print(f"Error reading file {filename}: {e}")
                continue
            
            # Close the code block and add a separator
            output.write("\n```")
            output.write("\n" + "=" * 40 + "\n\n")  # Add separator between file contents
            
            print(f"Processed: {filename}")  # Debugging output

    # If no Java files were found, print a message
    if not found_java_files:
        print("No .java files found in the folder.")
    else:
        print(f"All Java code has been saved to {output_file}")
