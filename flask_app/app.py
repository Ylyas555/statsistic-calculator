from flask import Flask, render_template, request, session, redirect, url_for
import random
import math

app = Flask(__name__)
app.secret_key = "super_secret_key"


# ============================
# HELPER FUNCTIONS
# ============================

def random_numbers(count, min_val=1, max_val=20):
    return [random.randint(min_val, max_val) for _ in range(count)]


def mean(nums):
    return sum(nums) / len(nums)


def sample_variance(nums):
    m = mean(nums)
    return sum((n - m) ** 2 for n in nums) / (len(nums) - 1)


def population_variance(nums):
    m = mean(nums)
    return sum((n - m) ** 2 for n in nums) / len(nums)


# ============================
# PRACTICE ONE (Mean, Median, Mode, Range)
# ============================

@app.route("/practice", methods=["GET", "POST"])
def practice():

    if "practice_one" not in session:
        session["practice_one"] = {
            "Mean": random_numbers(4),
            "Range": random_numbers(3)
        }

    results = {}

    if request.method == "POST":
        for topic, nums in session["practice_one"].items():
            user_input = request.form.get(topic, "").strip()

            if not user_input:
                results[topic] = "empty"

            elif not user_input.replace('.', '', 1).isdigit():
                results[topic] = "invalid"

            else:
                user_value = float(user_input)

                if topic == "Mean":
                    correct = mean(nums)
                elif topic == "Range":
                    correct = max(nums) - min(nums)

                results[topic] = {
                    "correct": round(user_value, 2) == round(correct, 2),
                    "user": user_input,
                    "answer": round(correct, 2)
                }

    return render_template("practice.html",
                           questions=session["practice_one"],
                           results=results)


# ============================
# PRACTICE TWO
# ============================

@app.route("/practice_two", methods=["GET", "POST"])
def practice_two():

    if "practice_two" not in session:
        nums = random_numbers(5)
        answer = round(math.sqrt(sample_variance(nums)), 2)

        session["practice_two"] = {
            "nums": nums,
            "answer": answer
        }

    results = None

    if request.method == "POST":
        user_input = request.form.get("answer", "").strip()

        if not user_input:
            results = "empty"

        elif not user_input.replace('.', '', 1).isdigit():
            results = "invalid"

        else:
            user_value = float(user_input)
            correct_answer = session["practice_two"]["answer"]

            results = {
                "correct": round(user_value, 2) == correct_answer,
                "user": user_input,
                "answer": correct_answer
            }

    return render_template("practice_two.html",
                           data=session["practice_two"],
                           results=results)


if __name__ == "__main__":
    app.run(debug=True)
