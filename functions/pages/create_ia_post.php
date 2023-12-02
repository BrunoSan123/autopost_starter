<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $command = $_POST["command"];
    $topics = $_POST["topics"];
    $llm = $_POST["llm"];
    $imageGenerators = $_POST["image_generators"];
    $temperature = $_POST["temperature"];
    $maxCtxTokens = $_POST["max_ctx_tokens"];
    $amountToGen = $_POST["amount_to_gen"];
    $repetitionPenalty = $_POST["repetition_penalty"];
    $bigTextCount = $_POST["big_text_count"];

    // You can now process the data as needed
    // For example, you can echo the values to verify the data is being received correctly
    echo "Command: $command<br>";
    echo "Topics: $topics<br>";
    echo "LLM: $llm<br>";
    echo "Image Generators: $imageGenerators<br>";
    echo "Temperature: $temperature<br>";
    echo "Max Ctx. Tokens: $maxCtxTokens<br>";
    echo "Amount to Generate: $amountToGen<br>";
    echo "Repetition Penalty: $repetitionPenalty<br>";
    echo "Big Text Count: $bigTextCount<br>";
}
?>
