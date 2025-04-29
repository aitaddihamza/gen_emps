from openai import OpenAI

client = OpenAI(
    base_url="https://api.netmind.ai/inference-api/openai/v1",
    api_key="da56e0822b5e4dadbc641d1cdd69a758",
)
chat_completion_response = client.chat.completions.create(
    model="google/gemma-3-12b-it",
    messages=[
        {
            "role": "user",
            "content": [
                {
                    "type": "text",
                    "text": "What's in the picture?"
                },
                {
                    "type": "image_url",
                    "image_url": {
                        "url": "https://github.com/sgl-project/sglang/blob/main/test/lang/example_image.png?raw=true"
                    }
                }
            ]
        }
    ],
    max_tokens = 512
)
print(chat_completion_response.choices[0].message.content)
