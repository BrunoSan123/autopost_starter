import os
from googletrans import Translator
import polib
from tqdm import tqdm
import re

def convert_language_code(original_code):
    # Mapping dictionary for converting specific language codes
    language_mapping = {
        "pt_BR": "pt",
        "en_US": "en",
        # Add more mappings as needed
    }
    return language_mapping.get(original_code, original_code)

def translate_po_files():
    # Get a list of all .po files in the current directory
    po_files = [file for file in os.listdir() if file.endswith(".po")]

    for po_file in po_files:
        # Extract language code from the filename using a more robust method
        language_match = re.search(r'-(\w{2}_[\w-]+)\.po', po_file)
        if language_match:
            original_language_code = language_match.group(1)
            language_code = convert_language_code(original_language_code)
        else:
            print(f"Unable to extract language code from {po_file}. Skipping.")
            continue

        # Load the .po file
        po = polib.pofile(po_file)

        # Initialize the Google Translate API
        translator = Translator()

        # Create a tqdm progress bar
        progress_bar = tqdm(po, desc=f"Translating {original_language_code} to {language_code}", unit="entry", leave=False)

        # Iterate through each entry in the .po file
        for entry in progress_bar:
            # Only process entries with msgid and msgstr
            if entry.msgid and not entry.msgstr:
                try:
                    # Use Google Translate to get the translation
                    translated_text = translator.translate(entry.msgid, dest=language_code).text

                    # Set the translated text to msgstr
                    entry.msgstr = translated_text

                    # Print the translated string
                    print(f"Translated: {entry.msgid} => {translated_text}")

                except Exception as e:
                    print(f"Error translating {entry.msgid}: {str(e)}. Skipping.")

        # Close the progress bar
        progress_bar.close()

        # Save the modified .po file
        po.save(f"autopost-{language_code} translated.po")

if __name__ == "__main__":
    translate_po_files()
